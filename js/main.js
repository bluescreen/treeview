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
    $('#download-button').on('click', function () {
        Turtle.export();
        return false;
    });
    Tree.setCanvas('canvas1');
    Tree.init(8, function (data) {
        Tree.draw('single');
        updateMatchTable(data);
    });
});
