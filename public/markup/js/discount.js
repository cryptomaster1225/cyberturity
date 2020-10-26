jQuery(document).ready(function($) {
    var ajaxEnabled = true;

    var $product = $('#order1_product');
    var $quantity = $('#order1_quantity');
    var $discount = $('#order1_discountCode');
    var $discountBtn = $('#order1_apply');

    var $total = $('[data-total]');
    var $subtotal = $('[data-subtotal]');

    var $discountResult = $('[data-discount-result]');
    var $discountText = $('[data-discount-validation]');
    var $discountError = $('[data-discount-error]');


    function addCommas(nStr)
    {
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
    }

    function updateResult() {
        $discountError.removeClass('d-block').addClass('d-none');
        $discountText.removeClass('d-block').addClass('d-none');

        $quantity.addClass('is-disabled').prop('disabled', true);
        $discount.addClass('is-disabled').prop('disabled', true);

        var data = {
            product: $product.val(),
            quantity: $quantity.val(),
            discountCode: $discount.val(),
        };

        if (ajaxEnabled) {
            ajaxEnabled = false;

            $.ajax({
                url: '/discount',
                method: 'POST',
                data,
            })
            .done(function(data) {
                if (data) {
                    $total.html('$' + addCommas(Number(data.total).toFixed(2)));
                    $subtotal.html('$' + addCommas(Number(data.subtotal).toFixed(2)));

                    if (data.discountValid) {
                        $discountResult.html('$' + addCommas(Number(data.discount).toFixed(2)));
                    } else if ($discount.val()) {
                        $discountResult.html('$' + addCommas(Number(0).toFixed(2)));
                        $discountText.removeClass('d-none').addClass('d-block');
                    }
                } else {
                    $discountError.html("We've encountered an error. Reload the page and try again.").removeClass('d-none').addClass('d-block');
                }
            })
            .fail(function( jqXHR, textStatus ) {
                console.log( "Request failed: " + textStatus );
                $discountError.html("We've encountered an error.<br>Reload the page and try again.").removeClass('d-none').addClass('d-block');
            })
            .always(function() {
                ajaxEnabled = true;

                $quantity.removeClass('is-disabled').removeAttr('disabled');
                $discount.removeClass('is-disabled').removeAttr('disabled');
            });
        }
    }

    $product.on('change', function(e) {
        updateResult();
    })

    $quantity.on('change', function(e) {
        updateResult();
    });

    $('input[type=text], input[type=number]').keypress(function (e) {
        if (e.which === 13) {
            updateResult();
            return false;
        }
    })

    $discountBtn.on('click', function(e) {
        e.preventDefault();
        updateResult();
    })

    $product.trigger('change');
})
