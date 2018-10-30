/* French initialisation */
jQuery(function($){

    $.datepicker.regional['fr'] = {
		closeText: 'Fermer',
		prevText: '&#x3c;Préc',
		nextText: 'Suiv&#x3e;',
		currentText: 'Aujourd\'hui',
		monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
		'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
		monthNamesShort: ['Jan','Fév','Mar','Avr','Mai','Jun',
		'Jul','Aoû','Sep','Oct','Nov','Déc'],
		dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
		dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
		dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa']
		};
	$.datepicker.setDefaults($.datepicker.regional['fr']);

    $( "#datePicker input").datepicker({
        changeMonth : true,
        changeYear : true,
        showButtonPanel : true,
        minDate : "-0d", // pas dans le passé
        maxDate : "+1y",
        altField : "#appbundle_ticket_visitDate",
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        beforeShowDay: function (date) {
            var array = ["11-01", "12-25", "05-01"]; // Jours de fermeture

            if (date.getDay() === 2 || date.getDay() === 0) {
                //Désactivation du mardi et dimanche
                return [false, ''];
            } else {
                //Désactivation des jours de fermeture
                var string = jQuery.datepicker.formatDate('mm-dd', date);
                return [ array.indexOf(string) === -1 ]
            }
        }
    });

});
