<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="js/turtle.js"></script>
<script type="text/javascript" src="js/tree.js"></script>
<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="js/underscore-min.js"></script>

<script type="text/template" id="matches-template">
    <table class="table table-condensed table-striped matches-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>White</th>
            <th>W</th>
            <td>:</td>
            <th>R</th>
            <th>Red</th>
            <th>Winner</th>
        </tr>
        </thead>
        <tbody>
        <% _.each(matches,function(match,key){ %>
        <tr>
            <td><%= match.id %></td>
            <td><%= match.title %></td>
            <td style="border-left: 1px solid #000"><%= match.white_name %></td>
            <td><%= match.points_white %></td>
            <td>:</td>
            <td><%= match.points_red %></td>
            <td style="border-right: 1px solid #000"><%= match.red_name %></td>
            <td><%= match.winner %></td>
        </tr>
        <% }); %>
        </tbody>
    </table>
</script>

<script>
    $(document).on('ready', function () {
        var template = $('#matches-template').html();

        function updateMatchTable(data) {
            console.log("Update", data.matches);
            $('#winner-placeholder').html("Winner: "+data.winner);

            data.matches = _.sortBy(data.matches, function (match) {
                return match.depth * -1;
            });

            var compiled = _.template(template);
            $('#matches-placeholder').html(compiled({
                matches: data.matches
            }));
        }


        $('#redraw-button').on('click', function () {
            var numParticipants = $('#numParticipants').val();
            var treeType        = $("#treeType").val();

            Tree.loadData(numParticipants, function (data) {
                Tree.draw(treeType);
                updateMatchTable(data);
            });
        });

        $('#numParticipants, #treeType').on('change', function () {
            console.log('redraw tree');
            var numParticipants = $('#numParticipants').val();
            var treeType        = $("#treeType").val();


            Tree.loadData(numParticipants, function (data) {
                Tree.draw(treeType);
                updateMatchTable(data);
            });
            return false;
        });
        $('#download-button').click(function () {
            Turtle.export();
            return false;
        });
        Tree.setCanvas('canvas1');
        Tree.init(8, function (data) {
            Tree.draw('single');
            updateMatchTable(data);
        });
    });
</script>
<style>

    .canvas.ui-resizable {
        border: 1px solid #ccc;
        float: left;
    }

    #links {
        position: relative;
        background: #fff;
    }

    #links a.link {
        position: absolute;
        color: #000;
        display: block;
        width: 100px;
        text-align: right;
        text-decoration: none;
        font-size: 12px;
    }

    #links .link:hover {
        color: #f00;
    }

    #canvas1 {
        border: 1px solid #ccc;
        background: #fff;
    }

    select.form-control {
        width: auto;
        display: inline-block;
    }

    .matches-table {
        white-space: nowrap;
    }

    #winner-placeholder{
        color:#f00;
        text-align: right;
    }

</style>

<div class="container" style="width:90%">
    <div class="row">
        <div class="col-sm-8">
            <h2>Elimination Tree</h2>

            <select id="numParticipants" class="form-control">
                <option value="2">2 participants</option>
                <option value="4">4 participants</option>
                <option value="8" selected>8 participants</option>
                <option value="16">16 participants</option>
                <option value="32">32 participants</option>
                <option value="64">64 participants</option>
                <option value="random">Random participants</option>
            </select>
            <select id="treeType" class="form-control">
                <option value="single" selected>Single Tree</option>
                <option value="double">Double Tree</option>
            </select>

            <button id="download-button" class="btn btn-primary">Download</button>
            <button id="redraw-button" class="btn btn-primary">Redraw</button>

            <div id="canvas_wrapper" style="max-width:100%">
                <canvas id="canvas1" class="canvas resizeable" width="1000" height="550"></canvas>
            </div>
        </div>
        <div class="col-sm-3">
            <h2 style="margin-bottom:1em;">Matches <span class="pull-right" id="winner-placeholder"></span>
            </h2>
            <div id="matches-placeholder"></div>

        </div>
    </div>
</div>