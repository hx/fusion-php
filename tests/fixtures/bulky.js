(function() {
    var foo = 1;
    var bar = 2;
    window.fb = function() {
        return foo + bar;
    }
}).call(this);
