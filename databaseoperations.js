var $Name;

function insertPokemon()
{
    alert ("in insertPokemon()")
    var Name, Type, Number;
    Name = JSON.stringify($('#Name').val());
    Type = JSON.stringify($('#Type').val());
    Number = JSON.stringify($('#Number').val());
    var ajax = insertPokemonAjax("insertPokemon", Name, Type, Number);
    ajax.done(insertPokemonCallback);
    ajax.fail(function() {
        alert("Failure in insertPokemon");
    });
}

function insertPokemonAjax(method, Name, Type, Number)
{
    return $.ajax({
        url: 'databaseoperations.php',
        type: 'POST',
        data: {method: method,
            Name: Name,
            Type: Type,
            Number: Number}
    });
}

function insertPokemonCallback(response_in) {
    $Name = response_in;
    response = JSON.parse(response_in);

    if (!response['success'])
    {
        $("#results").html("");
        alert("Insert failed.");
    }
    else
        getPokemon();
}

function onLoad()
{
    getPokemon();
}

function getPokemon()
{
    alert ("in getPokemon()")
    ajax = getPokemonAjax("getPokemon");
    ajax.done(getPokemonCallback);
    ajax.fail(function () {
        alert("Failure in getPokemon call to getPokemonAjax");
    });
}

function getPokemonAjax(method)
{
    return $.ajax({
        url: 'databaseoperations.php',
        type: 'POST',
        data: {method: method}
    });
}

function getPokemonCallback(response_in)
{
    response = JSON.parse(response_in);
    $Name = response["pokemon"];
    if (!response['success'])
        alert("getPokemon failed.");
    else
    {
        $('#NameSelect').find('option').remove();
        showPokemon($Name);
        $.each($Name,
            function(key, row)
            {
                $("#NameSelect").append($('<option>',
                    {
                        value: row[0].toString(),
                        text: row[1].toString()
                    }));
            })
    }
}

function showPokemon(Pokemon)
{
    var Pokemon_List = "";

    $.each(pokemon, function (key, value)
    {
        var itemString = "";
        $.each(value, function (key, item)
        {
            itemString += item + "&nbsp &nbsp &nbsp";
        });
        Pokemon_List += itemString + '<br>';
    });

    $("#results").html(Pokemon_List);
}

