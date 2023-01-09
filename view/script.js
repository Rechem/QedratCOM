$(document).ready(function () {

    //modal and login related stuff
    let isLogin = true;

    function toggleLogin() {
        if (isLogin) {
            $('#login-form').removeClass('active');
            $('#login-form input').prop('required',false)
            
            $('#login-form input[type=hidden]').attr('name', 'signup')
            $('#signup-form').addClass('active');
            $('#signup-form  input, #signup-form select').prop('required',true)
            isLogin = false;
        } else {
            $('#login-form').addClass('active');
            $('#login-form  input').prop('required',true)
            $('#login-form input[type=hidden]').attr('name', 'login')

            $('#signup-form').removeClass('active');
            $('#signup-form input, #signup-form select').prop('required',false)
            isLogin = true;
        }
    }

    $('#toggle-login-btn').on('click', toggleLogin)

    const open = $('#login-btn');
    const close = $('#close-icon');
    const modal = $('#modal-container');

    open.on('click', function () {
        modal.addClass('show');
    });

    close.on('click', function () {
        modal.removeClass('show');
    });

    // rechercher recette related stuff

    $('#search-form .trouver-recette').on('click', function () {
        const ingredients = [];
        $('div.chip').each(function () {
            ingredients.push($(this).data('id'));
        })
        if (ingredients.length > 0) {
            const url = 'IdeeRecette.php?ingredients=' + ingredients.join(',');
            window.location.href = url;
        }
    });

    $('#search-phrase').on('focusout', function () {
        removeItems();
    })

    function debounce(func, timeout = 300) {
        let timer;
        return function(...args) {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    $('#idee-section #results').on('mousedown', '.result-item', function () { selectItem($(this).data('id'), $(this).text()) });
    $('#add-recette-form #results').on('mousedown', '.result-item', function () {
        $("<div>").load('./AjouterRecette/AjouterRecette.php?ingredient=' + $(this).data('id') + ' .ingredients>.container>*',
        function() {
            $(".ingredients>.container").append($(this).html());
        })
        $('#search-phrase').val('');
        removeItems();
        // selectItem($(this).data('id'), $(this).text())
    });

    $('.container').on('click','.remove-ig-recette', function (e) {
        $(this).closest('div.ingredient').remove();
    })

    $('#idee-section #search-phrase').on('keyup focus',
    debounce(searchIngredientIdeeRecette));

    $('#add-recette-form #search-phrase').on('keyup focus',
    debounce(searchIngredientAjouterRecette));

    function searchIngredientIdeeRecette() {
        clearTimeout(debounce);
        if ($(this).val() === '')
            removeItems();
        else {
            const forbidenIds = []
            $('div.chip').each(function () {
                forbidenIds.push($(this).data('id'));
            })
            let url = './IdeeRecette/IdeeRecette.php' + '?q=' + $(this).val()
            if (forbidenIds.length > 0)
                url += '&ignore=' + forbidenIds.join(',');
            $('#results').load(url + ' .result-item')
        }
    }

    function searchIngredientAjouterRecette() {
        clearTimeout(debounce);
        if ($(this).val() === '')
            removeItems();
        else {
            const forbidenIds = []
            $('div.ingredient').each(function () {
                forbidenIds.push($(this).data('id'));
            })
            let url = './AjouterRecette/AjouterRecette.php' + '?q=' + $(this).val()
            if (forbidenIds.length > 0)
                url += '&ignore=' + forbidenIds.join(',');
            $('#results').load(url + ' .result-item')
        }
    }

    function selectItem(id, text) {
        const chip = $('<div>', { class: 'chip' }).data('id', id);

        const h6 = $('<h6>', { text });
        chip.append(h6)

        const removeButton = $('<ion-icon>', { name: "close-outline", class: "remove-icon" });
        removeButton.on('click', function (e) {
            $(this).closest('div.chip').remove();
        })

        chip.append(removeButton)

        $('#chips-container').append(chip)

        $('#search-phrase').val('');
        removeItems();
    }
    function removeItems() {
        //clear all the item
        let items = $('.result-item')
        items.each(function (_) {
            $(this).remove();
        });
    }

    //  fetes search stuff

    $('#fete-search-section #results').on('mousedown', '.result-item', function () {
        const url = 'Fete.php?fete=' + $(this).data('id');
        window.location.href = url;
    });

    $('#fete-search-section #search-phrase').on('keyup focus',
    debounce(function (_) {
        clearTimeout(debounce);
        if ($(this).val() === '')
            removeItems();
        else {
            let url = './Fete/Fete.php?q=' + $(this).val()
            $('#results').load(url + ' .result-item')
        }
    }));

    // home scroll related stuff

    $('.left-swipe-btn').on('click', function () {
        horizontalScroll.call(this, 1)
    });

    $('.right-swipe-btn').on('click', function () {
        horizontalScroll.call(this, -1)
    });

    function horizontalScroll(value) {

        const container = $(this).closest('.categorie');
        const scroll = $(this).siblings('.horizental-scroll');

        const maxScroll = container.width() - scroll.width() - parseFloat(scroll.css("margin-inline")) * 2

        let currentPostion = parseFloat(scroll.css("left"))
        if (currentPostion === 0 && maxScroll > 0)
            return

        currentPostion += value * 240;

        if (currentPostion >= 0) {
            currentPostion = 0
        }
        if (currentPostion <= maxScroll) {
            currentPostion = maxScroll
        }


        scroll.css({ "left": currentPostion + "px" })

    }

    // ingredients/etape stuff

    $('#ingredient-tab').on('click', function () {
        if (!$('#ingredients').hasClass('active')) {
            $('#ingredients').addClass('active')
            $('#ingredient-tab').addClass('active')
            $('#etapes').removeClass('active')
            $('#etape-tab').removeClass('active')
        }
    });

    $('#etape-tab').on('click', function () {
        if (!$('#etapes').hasClass('active')) {
            $('#etapes').addClass('active')
            $('#ingredients').removeClass('active')
            $('#ingredient-tab').removeClass('active')
            $('#etape-tab').addClass('active')
        }
    })

    // filter menu stuff

    $('.clear-siblings').on('click', function (e) {
        e.preventDefault();
        $(this).siblings('input').prop('checked', false);
    })

    $('#clear-form').on('click', function (e) {
        e.preventDefault();
        $(this).closest('form').trigger('reset');
        $('#filtrer-trier input[checked]').prop('checked', false);
        $('#filtrer-trier select').prop('selectedIndex', 0);
    })

    // etapes related stuff 

    $('textarea[name=etapes]').on('keyup focus',
    debounce(function (_) {
        clearTimeout(debounce);
        if ($(this).val() !== ''){
            let etapes = $(this).val().split(/\n\s*\n/);
            etapes = etapes.filter(e=>e.trim()!=='\n' && e.trim() !== '');
            etapes = etapes.map(e=>e.replace(/\n/g, ''))
            $('#apercu-etapes').empty();
            $('#apercu-etapes').append(etapes.map((e,i)=> i+1 + ' - ' + e).join('<br>'))
        }
    }))

})