<script type="text/javascript">
        var $k = jQuery.noConflict();
        $k(document).ready(function() {

            $k('#commande_visiteDate').blur(function() {
                visiteDate();
            });

            var form = document.querySelector("form");
            form.addEventListener("submit", function (e) {
                var regex = /\d/;
                var error = "";
                [].forEach.call(document.getElementsByClassName('name form-control'), function(v,i,a) {
                    if (regex.test(v.value)) {
                        error = "Votre nom ne peux pas contenir de chiffre";
                        alert(error);
                        e.preventDefault();
                    }
                });
            });


            function visiteDate() {
                var date = document.getElementById("commande_visiteDate");
                var places = 1000;
                if (date.value == "") {
                    document.getElementById("nombre").innerHTML = "";
                } {% for dispo in dispos %}else if (date.value == "{{ dispo.date }}") {
                    var dispo = {{ dispo.nombre }};
                    places = places - dispo;
                    document.getElementById("nombre").innerHTML = places;
                } {% endfor %}else {
                    document.getElementById("nombre").innerHTML = 1000;
                }
            }


            // On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
            var $container = $k('div#commande_tickets');


            // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
            var index = $container.find(':input').length;

            // On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
            $k('#add_ticket').click(function(e) {
                addTicket($container);

                e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                return false;
            });

            // On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
            if (index == 0) {
                addTicket($container);
            } else {
                // S'il existe déjà des catégories, on ajoute un lien de suppression pour chacune d'entre elles
                $container.children('div').each(function() {
                    addDeleteLink($k(this));
                });
            }

            // La fonction qui ajoute un formulaire CategoryType
            function addTicket($container) {
                // Dans le contenu de l'attribut « data-prototype », on remplace :
                // - le texte "__name__label__" qu'il contient par le label du champ
                // - le texte "__name__" qu'il contient par le numéro du champ
                var template = $container.attr('data-prototype')
                    .replace(/__name__label__/g, 'Ticket n°' + (index+1))
                    .replace(/__name__/g,        index)
                ;

                // On crée un objet jquery qui contient ce template
                var $prototype = $k(template);

                // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
                addDeleteLink($prototype);

                // On ajoute le prototype modifié à la fin de la balise <div>
                $container.append($prototype);

                // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
                index++;
            }

            // La fonction qui ajoute un lien de suppression d'une catégorie
            function addDeleteLink($prototype) {
                // Création du lien
                var $deleteLink = $k('<a href="#" class="btn btn-danger">Supprimer</a>');

                // Ajout du lien
                $prototype.append($deleteLink);

                // Ajout du listener sur le clic du lien pour effectivement supprimer la catégorie
                $deleteLink.click(function(e) {
                    $prototype.remove();

                    e.preventDefault(); // évite qu'un # apparaisse dans l'URL
                    return false;
                });
            }
        });


        var d = new Date();
        var n = d.getFullYear();
        var dates = [];

        for (var i = n; i < n + 10; i++) {
            var date1 = "01/05/" + i;
            var date2 = "01/11/" + i;
            var date3 = "25/12/" + i;
            dates.push(date1, date2, date3);
        }

        jQuery(document).ready(function($) {
            $('.datepicker').datepicker({
                format: "dd/mm/yyyy",
                maxViewMode: 0,
                language: "fr",
                daysOfWeekDisabled: "0,2",
                todayHighlight: true,
                startDate: new Date(),
                datesDisabled: dates
            });
        });
    </script>