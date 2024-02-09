console.log('countdown.js loaded');
document.addEventListener('DOMContentLoaded', function () {
    //ici on va chercher l'élément qui contient l'attribut data-event-date
    let eventElement = document.querySelector('.event-details');
    console.log(eventElement);
    //si l'élément existe
    if (eventElement) {
        // alors on récupère la valeur de l'attribut data-event-date
        let eventDate = eventElement.getAttribute('data-event-date');
        console.log(eventDate);
        //countDownDate est la date de l'événement, on cree un objet Date avec la valeur de l'attribut data-event-date
        countDownDate = new Date(eventDate).getTime();
        console.log(countDownDate);
        // ici on crée un intervalle qui va se répéter toutes les secondes, et qui va mettre à jour le contenu de l'élément avec l'id countdown
        // avec le temps restant avant l'événement
        let x = setInterval(function () {
            let now = new Date().getTime();
            let distance = countDownDate - now;
            //conversion du temps en jours, heures, minutes et secondes.
            let days = Math.floor(distance / (1000 * 60 * 60 * 24));
            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);
            //on met à jour le contenu de l'élément avec l'id countdown.
            if (distance < 86400000) {
                document.getElementById("countdown").innerHTML ="L'événemment commencera dans: " + hours + " heures " + minutes + " minutes " + seconds + " secondes ";
            }
            // si le compte à rebours est terminé, on affiche un message.
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("countdown").innerHTML = "L'événement est terminé";
            }
        }, 1000);
    }
});