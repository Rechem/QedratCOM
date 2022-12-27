$(document).ready(function () {

    //modal and login related stuff
    let isLogin = true;

    function drawSignup() {
        $('#modal h3').text('S\'inscrire')
        $('#modal input[type=submit]').val('Inscription')
        $('#toggle-login-btn').text('Déjà inscrit ? Se connecter')

        $('<h6>').text('Répeter le mot de passe').insertBefore('#modal input[type=submit]')
        $('<input>', { type: 'password', name: 'repeat-password' }).insertBefore('#modal input[type=submit]')
    }

    function drawLogin() {
        $('#modal h3').text('Se connecter')
        $('#modal input[type=submit]').val('Connexion')
        $('#toggle-login-btn').text('Nouveau utilisateur ? S\'inscrire')

        $('#modal h6:eq(2)').remove()
        $('#modal input[name=repeat-password]').remove()
    }

    function toggleLogin() {
        if (isLogin) {
            drawSignup()
            isLogin = false;
        } else {
            drawLogin()
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

    let names = [
        'Ayla',
        'Ayla2',
        'Ayla3',
        'Jake',
        'Sean',
        'Henry',
        'Brad',
        'Stephen',
        'Taylor',
        'Timmy',
        'Cathy',
        'John',
        'Amanda',
        'Amara',
        'Sam',
        'Sandy',
        'Danny',
        'Ellen',
        'Camille',
        'Chloe',
        'Emily',
        'Nadia',
        'Mitchell',
        'Harvey',
        'Lucy',
        'Amy',
        'Glen',
        'Peter',
    ];

    const searchPhrase = $('#search-phrase')[0];
    $('#search-form').on('focusout', function () {
        removeItems();
    })

    // debounce example
    // let debounce = null;
    // $('#input').on('keyup', function (e) {
    //     clearTimeout(debounce);
    //     debounce = setTimeout(function () {
    //         $.ajax({ url: 'someurl.jsp', data: { query: q }, type: 'GET' })
    //     }, 100);
    // });

    let debounce = null;
    $(searchPhrase).on('keyup focus', (_) => {
        clearTimeout(debounce);
        if (searchPhrase.value === '')
            removeItems();
        else
            debounce = setTimeout(function () {
                removeItems();
                let counter = 0
                for (let i of names) {
                    if (i.toLowerCase().startsWith(searchPhrase.value.toLowerCase())) {

                        const listItem = $('<li>', { class: 'result-item' });

                        listItem.on('mousedown', function () { selectItem(i) });

                        const text = $('<h6>');

                        text.append('<b>' + i.substring(0, searchPhrase.value.length) + '</b>')
                        text.append(i.substring(searchPhrase.value.length))

                        listItem.append(text);
                        $('#results').append(listItem);

                        counter++;
                        if (counter === 4)
                            break;
                    }
                }
            }, 200);
    });
    function selectItem(text) {
        const chip = $('<div>', { class: 'chip' });

        const h6 = $('<h6>', { text });
        chip.append(h6)

        const img = $('<img>',
            { src: '../../public/icons/add_white.svg', alt: 'remove', class: 'remove-icon' })
        img.on('click', function (e) {
            $(this).closest('div.chip').remove();
        })

        chip.append(img)

        $('#chips-container').append(chip)

        searchPhrase.value = '';
        removeItems();
    }
    function removeItems() {
        //clear all the item
        let items = $('.result-item')
        items.each(function (_) {
            $(this).remove();
        });
    }

    // scroll related stuff

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

})