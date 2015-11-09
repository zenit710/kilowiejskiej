var kw = 'Kilo Wiejskiej';
var homeTeam = null;
var awayTeam = null;
var homeScore = null;
var awayScore = null;
var isKiloHome = null;
var played = null;
var performanceLimit = 15;

$(document).ready(function(){
    prepareScorersFields();
});

function prepareScorersFields(){
    homeTeam = $('#home_name');
    awayTeam = $('#away_name');
    homeScore = $('#home_goals');
    awayScore = $('#away_goals');
    played = $('#is_played');
    isKiloHome = homeTeam.val() === kw ? true : awayTeam.val() === kw ? false : null;

    homeTeam.change(function(){
        isKiloHome = homeTeam.val() === kw ? true : awayTeam.val() === kw ? false : null;
        if(homeTeam.val() === kw){
            isKiloHome = true;
            homeScore.change();
        }
    });

    awayTeam.change(function(){
        isKiloHome = homeTeam.val() === kw ? true : awayTeam.val() === kw ? false : null;
        if(awayTeam.val() === kw){
            isKiloHome = false;
            awayScore.change();
        }
    });

    homeScore.change(function(){
        if(isKiloHome === null){
            return;
        }
        if(isKiloHome){
           var goals = parseInt(homeScore.val());
           createScorersFields(goals);
       } 
    });

    awayScore.change(function(){
        if(isKiloHome === null){
            return;
        }   
        if(!isKiloHome){
           var goals = parseInt(awayScore.val());
           createScorersFields(goals);
       } 
    });
    
    played.change(function(){
        var goals = parseInt(isKiloHome ? homeScore.val() : awayScore.val());
        createScorersFields(goals);
        createPerformanceFields();
    });

    if(isKiloHome !== null){
        var goals = parseInt(isKiloHome ? homeScore.val() : awayScore.val());
        createScorersFields(goals);
    }

    createScorersFields(parseInt(isKiloHome ? homeScore.val() : awayScore.val()));
    createPerformanceFields();
}

function createScorersFields(amount){
    if(matchPlayed() && kiloPlays()){
        var scorersContainer = $('#scorers');
        scorersContainer.html('<div>Strzelcy:</div>');
        for(var i= 0; i< amount; i++){
            var html = '<select name="scorer_'+i+'">';
            for(var j= 0; j< playersObjectArray.length; j++){
                var playerName = playersObjectArray[j].surname + ' ' + playersObjectArray[j].name;
                html+= '<option value="'+ playersObjectArray[j].id +'">' + playerName + '</option>';
            }
            html+= '</select>';
            scorersContainer.append(html);
        }
    }
}

function matchPlayed(){
    return played.val();
}

function kiloPlays(){
    return homeTeam.val() === kw || awayTeam.val() === kw ? true : false;
}

function createPerformanceFields(){
    if(matchPlayed() && kiloPlays()){
        var performancesContainer = $('#performances');
        performancesContainer.html('<div>Skład:</div>');
        for(var i = 0; i< performanceLimit;i++) {
            var html = '<select name="perform_'+i+'">';
            for(var j= 0; j< playersObjectArray.length; j++){
                var playerName = playersObjectArray[j].surname + ' ' + playersObjectArray[j].name;
                html+= '<option value="'+ playersObjectArray[j].id +'">' + playerName + '</option>';
            }
            html+= '</select>';
            performancesContainer.append(html);
        }
    }
}