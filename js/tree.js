var DIR              = {
    LEFT:  0,
    RIGHT: 270,
    UP:    180,
    DOWN:  360
};
var Node             = function (pos) {
    this.x = pos.x;
    this.y = pos.y;
};
var BranchNode       = function (pos) {
    this.x = pos.x;
    this.y = pos.y;
};
var LeafNode         = function (pos) {
    this.x = pos.x;
    this.y = pos.y;
};
LeafNode.prototype   = Object.create(Node.prototype);
BranchNode.prototype = Object.create(Node.prototype);


var Tree = {
    root:     null,
    branches: [],
    leafs:    [],
    data:     [],
    depth:    6,
    type:     null,

    init:     function (num, callback) {
        this.loadData(num, callback);
    },
    loadData: function (num, callback) {
        Turtle.clearCanvas();
        Turtle.setColor("black");
        Turtle.drawText('Loading ...', 50, 50);

        Tree.branches = [];
        Tree.leafs    = [];

        var request = $.ajax({
            type:       'POST',
            url:        "/data.php?num=" + num,
            dataType:   'json',
            beforeSend: function (data) {
                $('#loading').show();
            },
            success:    function (data) {
                $('#loading').hide();
                console.log(data);
                Tree.data = data;
                if (callback != undefined) callback(data);
            }
        });
    },

    drawMatches: function (matches, nodes) {

        for (var i in matches) {
            var match = matches[i];
            var node  = nodes[i];

            Turtle.setColor('red');
            //Turtle.drawText(match.points_red,node.x+5, node.y+35);
            //Turtle.drawText(match.points_white,node.x+5, node.y-26);
            //Tree.addLink(match.title, match.link, node.x + 10, node.y + 10);

            Turtle.setColor('black');
            Turtle.drawText(match.title, node.x + 10, node.y + 20);
        }
    },

    drawNames: function (participants, nodes) {

        Turtle.setColor('black');
        var numNodes = nodes.length;
        for (var i in participants) {
            var p      = participants[i];
            var node   = nodes[i];
            var offset = 100;

            if (Tree.type != 'single') {
                offset = (i < (numNodes / 2)) ? -20 : 70;
            }
            Turtle.drawText(p.name + " (" + p.id + ")", node.x - offset, node.y + 5);
        }
    },

    addLink: function (text, link, x, y) {
        $('#links').append('<a class="link open-dialog" style="position: absolute; left:' + Math.round(x) + 'px; top:' + Math.round(y) + 'px;" href="' + link + '">' + text + '</a>');
    },

    reset: function () {
        Turtle.clear();
        $('#links .link').remove();
        this.branches = [];
        this.leafs    = [];
    },

    drawSingleTree: function (depth, dir) {
        var width   = Turtle.width;
        var height  = Turtle.height;
        var l       = depth * 30;
        var v       = width / (depth);
        var padding = 10;

        Turtle.clearCanvas();
        Turtle.setColor('black');

        switch (dir) {
            case 0:   // up
                Turtle.setPos(width / 2, height - padding);
                v = height / depth;
                break;
            case 90:
                Turtle.setPos(padding, height / 2);
                v = width / (depth + 2);
                l = height / (depth + 2);
                break;
            case 180:
                Turtle.setPos(width / 2, padding);
                v = height / depth;
                l = width / (depth);
                break;
            case 270:
                Turtle.setPos(width - padding, height / 2);
                v = width / (depth + 2);
                l = height / (depth + 2);
                break;
        }
        l += 30 * (depth * 0.2)
        Tree.root = new Node(Turtle.getPos());

        Turtle.turnTo(dir);
        Tree.drawTree(depth, l, v);
    },

    drawDoubleTree: function (depth) {
        console.log('depth', depth);
        var padding = 60 / depth;

        var l = Turtle.height / ((depth + 1) * 2) + 70;
        var v = (Turtle.width / ((depth + 1) * 2)) - padding;

        Turtle.setPos(Turtle.width / 2, Turtle.height / 2);

        var startPos = Turtle.getPos();
        Tree.branches.push(new BranchNode(startPos));

        startPos.y -= l;
        Tree.root = new Node(startPos);

        Turtle.turnTo(90);
        Tree.drawTree(depth, l, v);
        Turtle.turnTo(270);
        Tree.drawTree(depth, l, v);
        Turtle.turnTo(0).forward(l);
    },

    setCanvas: function (id) {
        Turtle.init(id);
    },

    draw: function (type) {
        var $this  = Tree;
        $this.type = type;
        $this.root = null;

        Turtle.clearCanvas();

        if (type == 'single') {
            $this.drawSingleTree(this.data.depth + 1, 270);
        } else {
            $this.drawDoubleTree(this.data.depth);
        }

        var mappedLeafs = $this.mapLeafsToParticpants($this.data.participants, Tree.leafs);

        $this.drawMatches($this.data.matches, Tree.branches);
        $this.drawNames($this.data.participants, Tree.leafs);
        $this.drawPaths($this.data.paths, mappedLeafs);

        Turtle.setColor('red');
        $this.markNodes(Tree.branches);

        Turtle.setColor('blue');
        $this.markNodes(Tree.leafs);
    },

    mapLeafsToParticpants: function (participants, leafs) {
        var mappedLeafs = [];
        _.each(participants, function (participant, key) {
            leafs[key].name             = participant.name;
            mappedLeafs[participant.id] = leafs[key];
        });
        return mappedLeafs;
    },

    drawPaths: function (paths, mappedLeafs) {
        _.each(paths, function (path, particpantId) {
            if (_.isEmpty(path)) {
                return;
            }
            var nodeList = _.map(path, function (key) {
                return Tree.branches[key];
            });
            nodeList.push(mappedLeafs[particpantId]);
            if (_.contains(path, 0) && Tree.root instanceof Node) { // Add Root node
                nodeList.unshift(Tree.root);
            }
            //console.log("Nodes", particpantId, path, nodeList);
            Tree.connectNodeList(nodeList);
        });
    },

    connectNodeList: function (nodes) {
        var $this = Tree;
        _.each(nodes, function (node, key) {
            var next_nr = key + 1;
            var next    = (next_nr != undefined) ? nodes[next_nr] : null;
            if (node instanceof Node && next instanceof Node) {
                $this.connectNodes(node, next);
            }
        })
    },

    connectNodes: function (node1, node2) {
        var oldLineWidth = Turtle.g.lineWidth;
        Turtle.setColor('red');
        Turtle.g.lineWidth = 4;
        Turtle.connect(node1.x, node1.y, node2.x, node2.y);
        Turtle.g.lineWidth = oldLineWidth;
    },

    markNodes: function (nodes) {
        for (var i in nodes) {
            var node = nodes[i];
            Turtle.drawCircle(node.x, node.y, 5);
            //Turtle.drawText(i, node.x - 15, node.y + 20);
        }
    },

    drawTree: function (depth, l, v) {
        var t = Turtle;
        if (depth > 0) {
            t.forward(v).right(90).show();
            Tree.branches.push(new BranchNode(t.getPos()));
            t.forward(l).left(90);

            this.drawTree(depth - 1, l / 2, v);
            if (depth == 1) {
                t.forward(v);
                Tree.leafs.push(new LeafNode(t.getPos()));
                t.backward(v);
            }
            t.right(90).backward(2 * l).left(90);


            this.drawTree(depth - 1, l / 2, v);
            if (depth == 1) {
                t.forward(v);
                Tree.leafs.push(new LeafNode(t.getPos()));
                t.backward(v);
            }
            t.right(90).forward(l).left(90).backward(v);
        }
    },

    drawBracket: function (l, v) {
        var t = Turtle;
        t.show();
        t.forward(l).right(90).remember().forward(v).left(90);
        //	t.forward(l).goback().backward(v).left(90).forward(l).goback().right(90).forward(l).show();
    }
};