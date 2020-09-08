function deg2rad(angle) {
    return (angle / 180) * Math.PI;
}

var Turtle = {
    g:      null,
    canvas: null,
    posX:   0, posY: 0,
    store:  {x: 0, y: 0, angle: 0},
    width:  0, height: 0,
    angle:  0,
    pen:    true,


    init: function (id) {
        canvas        = document.getElementById(id);
        var wrapper   = $('#canvas_wrapper');
        canvas.width  = wrapper.width() - 50;
        canvas.height = wrapper.height();

        this.canvas = canvas;
        this.g      = canvas.getContext("2d");
        this.width  = canvas.width;
        this.height = canvas.height;

        this.setColor('black');
    },

    export: function () {
        var url = this.canvas.toDataURL('image/png');
        var iframe = "<iframe width='100%' height='100%' src='" + url + "'></iframe>"
        var x = window.open();
        x.document.open();
        x.document.write(iframe);
        x.document.close();
    },

    clearCanvas: function () {
        this.g.clearRect(0, 0, this.width, this.height);
    },

    remember: function () {
        this.store.x     = this.posX;
        this.store.y     = this.posY;
        this.store.angle = this.angle;
        return this;
    },

    goback: function () {
        this.posX  = this.store.x;
        this.posY  = this.store.y;
        this.angle = this.store.angle;
        return this;
    },

    show: function () {
        this.drawCircle(this.posX, this.posY, 5);
        return this;
    },

    move: function (newX, newY) {
        this.drawLine(this.posX, this.posY, newX, newY);

        this.posX = newX;
        this.posY = newY;
        return this;
    },

    toHome: function () {
        this.angle = 0;
        this.setPos(this.width / 2, this.height / 2);
        return this;
    },

    setPos: function (x, y) {
        this.posX = x;
        this.posY = y;
        return this;
    },

    getPos: function () {
        return {x: this.posX, y: this.posY};
    },

    turnTo: function (angle) {
        this.angle = angle;
        return this;
    },

    forward: function (length) {
        var newX = this.posX + (Math.sin(deg2rad(this.angle)) * length);
        var newY = this.posY - (Math.cos(deg2rad(this.angle)) * length);
        this.move(newX, newY);
        return this;
    },

    backward: function (length) {
        this.forward(-length);
        return this;
    },

    left: function (d) {
        this.angle -= d;
        return this;
    },

    right: function (d) {
        this.angle += d;
        return this;
    },

    setColor: function (color) {
        var g         = this.g;
        g.fillStyle   = color;
        g.strokeStyle = color;
        return this;
    },

    drawText: function (text, x, y) {
        this.g.font = '14px sans-serif';
        this.g.fillText(text, x, y);
    },

    drawLine: function (x1, y1, x2, y2) {
        var g = this.g;
        g.beginPath();
        g.moveTo(x1, y1);
        g.lineTo(x2, y2);
        g.stroke();
        g.closePath();
    },

    drawCircle:     function (x, y, s) {
        var g = Turtle.g;
        g.beginPath();
        g.arc(x, y, s, 0, Math.PI * 2, true);
        g.fill();
        g.closePath();
    },
    drawCircleLine: function (x, y, s) {
        var g = Turtle.g;
        g.beginPath();
        g.arc(x, y, s, 0, Math.PI * 2, true);
        g.stroke();
        g.closePath();
    },

    connect: function (x1, y1, x2, y2) {
        var g = Turtle.g;
        g.beginPath();
        g.moveTo(x1, y1);
        g.lineTo(x1, y2);
        g.lineTo(x2, y2);
        g.stroke();
        g.closePath();
    }
};
