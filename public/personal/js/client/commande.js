$( document ).ready(function() {
    "use strict";

    //Quand on effectue une recherche
    $( document ).on('keyup', "div#commandes #recherche", function() {
        panelRefreshCommandes().show();
        reloadCommandesTable(1, $("div#commandes #statut").val(), $(this).val(), $("div#commandes #date input").val());
    });

    //Datetime picker pour les commandes
    $('div#commandes #date').datetimepicker({
        format: 'YYYY-MM-DD',
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        }
    }).on('dp.change', function() {
        // if($(this).data("DateTimePicker").date() === null)
        //     $(this).data("DateTimePicker").date(moment());
        panelRefreshCommandes().show();
        reloadCommandesTable(1, $("div#commandes #statut").val(), $("div#commandes #recherche").val(), $("div#commandes #date input").val());
    });

    //Quand on selectionne le statut de commandes
    $( document ).on('change', "div#commandes #statut", function() {
        panelRefreshCommandes().show();
        reloadCommandesTable(1, $(this).val(), $("div#commandes #recherche").val(), $("div#commandes #date input").val());
    });

    //On annuler le filtre
    $( document ).on('click', "div#commandes #annuler-filter", function() {
        panelRefreshCommandes().show();
        reloadCommandesTable(1, '', '', '');
    });

    $( document ).on('click', "div#commandes a#page", function( event ) {
        event.preventDefault(); //prevent default action 
        var page = $( this ).attr('data-page');
        panelRefreshCommandes().show();
        reloadCommandesTable(page, $("div#commandes #statut").val(), $("div#commandes #recherche").val(), $("div#commandes #date input").val());
    });
    //On va a la page precedent
    $( document ).on('click', "div#commandes  a#page-precedente", function( event ) {
        event.preventDefault(); //prevent default action 
        var page = parseInt($('li.active a#page').attr('data-page')) - 1;
        panelRefreshCommandes().show();
        reloadCommandesTable(page, $("div#commandes #statut").val(), $("div#commandes #recherche").val(), $("div#commandes #date input").val());
    });
    //On va a la page suivante
    $( document ).on('click', "div#commandes  a#page-suivante", function( event ) {
        event.preventDefault(); //prevent default action 
        var page = parseInt($('li.active a#page').attr('data-page')) + 1;
        panelRefreshCommandes().show();
        reloadCommandesTable(page, $("div#commandes #statut").val(), $("div#commandes #recherche").val(), $("div#commandes #date input").val());
    });

    function reloadCommandesTable(page, statut, recherche, date){
    	var client = parseInt($("#clientID").attr('data-client'));
        $.ajax({
            url : '/admin/commande/ajax/commandes-client/?page='+page+'&client='+client+'&statut='+statut+'&recherche='+recherche+'&date='+date,
            type: 'GET',
        }).done(function(response){ //
            $("div#commandes").html(response);
            panelRefreshCommandes().hide();
        }).fail(function(xhr, status, error){
            panelRefreshCommandes().hide();
        });
    }

    function panelRefreshCommandes(){
        var panelToRefresh = $('.reload-commandes').closest('.panel').find('.refresh-container');
        return panelToRefresh;
        // panelToRefresh.show();
    }

    //On ouvre les details de la commande
    $( document ).on( "click", "div#commandes button#details", function( event ) {
        $(".modal-content").html('');
        var id = parseInt($( this ).attr('data-id'));

        $('#modal-commande').modal({
            backdrop: 'static',
            show: true
        });

        $.ajax({
            url : '/admin/client/ajax/commande/'+id,
            type: 'GET',
        }).done(function(response){ //
            $(".modal-content").html(response);
            $(" .custom-loader ").hide();
        }).fail(function(xhr, status, error){
            $('#modal-commande').modal('hide');
            $.toast().reset('all');
            $("body").removeAttr('class');
            $.toast({
                heading: 'Oops! ' + xhr,
                text: 'Impossible de charger cette commande.',
                position: 'top-right',
                loaderBg:'#fec107',
                icon: 'error',
                hideAfter: 3500
            });
            console.log(xhr);
        });
    });
	
	
});