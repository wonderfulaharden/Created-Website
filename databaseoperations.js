var Generation;
var Pokemon;




function onLoad() {
    //alert("In onLoad()");

    getGeneration(false);
    getPokemon(false);

}






function insertPokemon() {
    var GenerationID,
        Name,
        Type;
        Number;
    GenerationID = JSON.stringify($('#Generation option:selected').val());
    Name = JSON.stringify($('#Name').val());
    Type = JSON.stringify($('#Type').val());
    Number = JSON.stringify($('#Number').val());
    ajax = ajaxinsertPokemon("insertPokemon", GenerationID, Name, Type);
    ajax.done(insertPokemonCallback);
    ajax.fail(function() {
        alert("Failure");
    });
}

function ajaxinsertPokemon(method, GenerationID, Name, Type, Number) {
    return $.ajax({
        url : 'databaseoperations.php',
        type : 'POST',
        data : {
            method : method,
            GenerationID : GenerationID,
            Name : Name,
            Type : Type,
            Number: Number
        }
    });
}

function insertPokemonCallback(response_in) {
    response = JSON.parse(response_in);

    if (!response['success']) {
        $("#results").html("");
        alert("Insert failed on query:" + '\n' + response['querystring']);
        getPokemon(false);
        getGeneration(false);
    } else {
        $("results").html(response['querystring'] + '<br>' + response['success'] + '<br>');
        getPokemon(false);
        getGeneration(false);
    }
}

function showPokemon(Pokemon) {
    //alert("In showSeries()");
    //alert(Series);
    var PokemonList = "";
    $.each(Pokemon, function(key, value) {
        var itemString = "";
        $.each(value, function(key, item) {
            itemString += item + "\t \t";
        });
        PokemonList += itemString + '<br>';
    });
    $("#results").html(PokemonList);
}

function getPokemon() {
    //alert("In getSeries()");
    ajax = ajaxgetPokemon("getPokemon");
    ajax.done(getPokemonCallback);
    ajax.fail(function() {
        alert("Failure");
    });
}

function ajaxgetPokemon(method) {
    //alert("In ajaxgetSeries()");
    return $.ajax({
        url : 'databaseoperations.php',
        type : 'POST',
        data : {
            method : method
        }
    });
}

function getPokemonCallback(response_in) {
    //alert(response_in);
    var response = JSON.parse(response_in);
    Pokemon = response["Pokemon"];
    if (!response['success']) {
        $("#results").html("getPokemon() failed");
    } else {
        showPokemon(Pokemon);
    }
}

function getGeneration() {
    //alert("In getPublishers()");
    ajax = ajaxgetGeneration("getGeneration");
    ajax.done(getGenerationCallback);
    ajax.fail(function() {
        alert("Failure");
    });
}

function ajaxgetGeneration(method) {
    //alert("In ajaxgetPublishers()");
    return $.ajax({
        url : 'databaseoperations.php',
        type : 'POST',
        data : {
            method : method
        }
    });
}

function getGenerationCallback(response_in) {
    //alert("In getPublishersCallback()");
    //alert(response_in);
    response = JSON.parse(response_in);
    $Generation = response["Generation"];
    //alert($Publisher);
    if (!response['success']) {
        alert('Failed in getGenerationCallback');
        $("#results").html("getGeneration failed");
    } else {
        $('#Generation').find('option').remove();
        //alert($Publisher);
        $.each($Generation, function(key, columns) {
            $("#Generation").append($('<option>', {
                //value : columns[0].toString(),
                text : columns[1].toString()
            }));
        });
    }
}
