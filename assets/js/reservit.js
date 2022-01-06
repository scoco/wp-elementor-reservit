(function ($) {
    let ageFields = [];

    const bookingUrl = new URL("https://secure.reservit.com/reservit/reserhotel.php?lang=FR");

    let input = $('<input type="text" />');
    let newFields = $('');

    $('#enfants').bind('blur keyup change', function () {
        var n = this.value || 0;
        if (n + 1) {
            if (n > newFields.length) {
                addFields(n);
            } else {
                removeFields(n);
            }
        }
    });

    function addFields(n) {
        for (i = newFields.length; i < n; i++) {
            let newAge = input.clone();
            newFields = newFields.add(newAge);
            newAge.appendTo('#ages');
            newAge.attr("id", i++ + 1);
            ageFields.push(newAge);
            for (var i = 0; i < ageFields.length; i++) {
            }
        }
    }

    function removeFields(n) {
        ageFields.length = 0;
        for (var i = 0; i < ageFields.length; i++) {
        }
        let removeField = newFields.slice(n).remove();
        newFields = newFields.not(removeField);
    }

    function getBooking() {
        let checkIn = document.getElementById("check_in").value.split('/');
        let nuits = document.getElementById("nuits").value;
        let chambres = document.getElementById("chambres").value;
        let adultes = document.getElementById("adultes").value;
        let enfants = document.getElementById("enfants").value;
        let chaine = document.getElementById("id").value;
        let hotel = document.getElementById("hotelid").value;

        bookingUrl.searchParams.append("fday", checkIn[0]);
        bookingUrl.searchParams.append("fmonth", checkIn[1]);
        bookingUrl.searchParams.append("fyear", checkIn[2]);
        bookingUrl.searchParams.append("nbnights", nuits);
        bookingUrl.searchParams.append("numroom", chambres);
        bookingUrl.searchParams.append("nbadt", adultes);
        bookingUrl.searchParams.append("nbchd", enfants);
        bookingUrl.searchParams.append("id", chaine);
        bookingUrl.searchParams.append("hotelid", hotel);

        for (var i = 0; i < ageFields.length; i++) {
            console.log('input1');
            console.log(i);
            // bookingUrl.searchParams.append('ages1', 2);
        }
        //`&ages${i.attr('id')}`
        console.log(bookingUrl);
        let win = window.open(bookingUrl, '_blank');
    }

    $('#check_avail_home').on('submit', function () {
        getBooking();
    });
    
    $(window).on("load", function () {
        $('.datepicker').pickadate({
            format: 'dd/mm/yyyy',
            formatSubmit: 'dd/mm/yyyy',
        });
    });

    // Cette URL inclut les paramètres standards et couramment utlisés.Vous pouvez l'affiner en incluant d'autres paramètres plus avancés tels que:
    // fday = DD : DD correspond à la date d’arrivée à l’hôtel
    // fmonth = MM : MM correspond au mois d’arrivée à l’hôtel
    // fyear = YYYY : YYYY correspond à l’année d’arrivée à l’hôtel
    // nbnights = NN : NN correspond au nombre de nuits réservées
    // numroom = EE : EE correspond au nombre de chambres réservées
    // nbadt = ZZ : ZZ correspond au nombre d’adultes
    // nbchd = X & ages1=AA X correspond au nombre d’enfants.AA correspond à l’âge de l’enfant(Champ obligatoire si le nombre d’enfants est renseigné)
    // discountcode = CCCCC : CCCCC correspond au code promotionnel
})(jQuery);
