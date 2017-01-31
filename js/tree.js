var DIR = {
    LEFT:0,
    RIGHT:270,
    UP: 180,
    DOWN:360
};

var Tree = {
    branches: [],
    leafs:    [],
    data:     [],
    depth:    6,

    init:     function (callback) {
        this.loadData(callback);
    },
    loadData: function (callback) {
        var request = $.ajax({
            type:       'POST',
            url:        "/data.php",
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

            //		Turtle.setColor('red');
            //		Turtle.drawText(match.points2,node.x+5, node.y+35);
            //		Turtle.drawText(match.points1,node.x+5, node.y-26);
            Tree.addLink(match.title, match.link, node.x + 10, node.y + 10);

            Turtle.setColor('black');
            Turtle.drawText(match.title, node.x + 10, node.y + 20);
        }
    },

    drawNames: function (participants, nodes) {
        Turtle.setColor('black');
        for (var i in participants) {
            var p    = participants[i];
            var node = nodes[i];

            Turtle.drawText(p.name, node.x - 70, node.y + 5);
        }
    },

    drawPaths: function (paths) {
        console.log(paths);
        for (var i in paths) {

            var path = paths[i];
            Tree.markPath(path, Tree.branches);

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
        Turtle.turnTo(dir);
        Tree.drawTree(depth, l, v);
    },

    drawDoubleTree: function (depth) {
        console.log('depth', depth);
        var padding = 30;

        var l = Turtle.height / ((depth + 1) * 2);
        var v = (Turtle.width / ((depth + 1) * 2)) - padding;

        Turtle.setPos(Turtle.width / 2, Turtle.height / 2)
        Tree.branches.push(Turtle.getPos());

        Turtle.turnTo(90);
        Tree.drawTree(depth, l, v);
        Turtle.turnTo(270);
        Tree.drawTree(depth, l, v);
        Turtle.turnTo(0).forward(l);
    },

    setCanvas: function (id) {
        Turtle.init(id);
    },

    redraw: function () {
        this.draw(3);
    },

    draw: function () {
        var $this = Tree;

        Turtle.clearCanvas();
//		$this.drawSingleTree(3,90);
        $this.drawDoubleTree(this.data.depth);

        Turtle.setColor('red');
        $this.markNodes(Tree.branches);

        Turtle.setColor('blue');
        $this.markNodes(Tree.leafs);

        console.log($this.data.matches);
        console.log(Tree.branches);

        $this.drawMatches($this.data.matches, Tree.branches);
        $this.drawNames($this.data.participants, Tree.leafs);
        //	$this.drawPaths($this.data.paths);
    },

    markNodes: function (nodes) {
        for (var i in nodes) {
            var node = nodes[i];
            Turtle.drawCircle(node.x, node.y, 5);
            Turtle.drawText(i, node.x + 5, node.y + 20);
        }
    },

    markPath: function (path, nodes) {
        for (var key in path) {
            var k       = parseInt(key);
            var val     = path[k];
            var next_nr = path[k + 1];
            var node    = nodes[val];
            var next    = (next_nr != undefined) ? nodes[next_nr] : null;

            console.log(key, val, '-> ', next_nr);
            if (next) {
                this.connectNodes(node, next);
            }
        }

    },

    connectNodes: function (node1, node2) {
        Turtle.setColor('red');
        Turtle.g.lineWidth = 6;
        Turtle.connect(node1.x, node1.y, node2.x, node2.y);
    },

    drawTree: function (depth, l, v) {
        var t = Turtle;
        if (depth > 0) {
            t.forward(v).right(90).show();
            Tree.branches.push(t.getPos());
            t.forward(l).left(90);

            this.drawTree(depth - 1, l / 2, v);
            if (depth == 1) {
                t.forward(v);
                Tree.leafs.push(t.getPos());
                t.backward(v);
            }
            t.right(90).backward(2 * l).left(90);


            this.drawTree(depth - 1, l / 2, v);
            if (depth == 1) {
                t.forward(v);
                Tree.leafs.push(t.getPos());
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