var flachMsg = $(".alert");

console.log(flachMsg);

if(flachMsg.length>0){
    setTimeout(function(){flachMsg.fadeOut("slow")},3000);
}

//c'est un event onload ?
// on récupère le bouton : on check l'événement clic
// et au clic on réalise la fonction {
$(".js-add-wl-btn").on("click",function (event) {
    // on évite l'évenement normalement déclencher par le bouton
    event.preventDefault();

    $(this).addClass("hidden");
    $(this.nextElementSibling).removeClass("hidden");

    // on fait une requete ajax
    $.ajax({
        // qui correspond à la route qu'on a mis dans l'attribut data ajax url ici api_watchlist_add
        url: $(this).attr("data-ajax-url"),
        type: "POST"
    }).done(function (response) { // une fois que c'est fait on écoute la réponse
        //si le film est dans la watchlist
        if (response.status == "alreadyInList") {
            console.log(response);
            alert("already in!");
            //on montre le bouton remove
            $(".response.imdbId-remove").removeClass("hidden");
            $(".response.imdbId-add").addClass("hidden");
        }
        else {

            if (response.status === "ok") {
                console.log(response);
                alert("add to wishlist");


            } else {
                if (response.status === "error") {
                    console.log(response);
                    alert(response.libelle);
                }
            }

        }

    });

});


$(".js-remove-wl-btn").on("click",function (event){
    // on évite l'évenement normalement déclencher par le bouton
    event.preventDefault();

    $(this).addClass("hidden");
    $(this.previousElementSibling).removeClass("hidden");

    // on fait une requete ajax
    $.ajax({
        // qui correspond à la route qu'on a mis dans l'attribut data ajax url ici api_watchlist_add
        url: $(this).attr("data-ajax-url"),
        type: "POST"
    }).
    done(function(response){ // une fois que c'est fait on écoute la réponse
        //si le film est dans la watchlist
        if (response.status=="NotIn")
        {
            console.log(response);
            alert("Can't remove, not in list!");
            //on montre le bouton remove
            $(".response.imdbId-add").removeClass("hidden");
            $(".response.imdbId-remove").addClass("hidden");
        }
        else {
            if (response.status === "ok") {
                console.log(response);
                alert("remove from wishlist");


            } else {
                if (response.status === "error") {
                    console.log(response);
                    alert(response.libelle);
                }
            }
        }

    });

});
