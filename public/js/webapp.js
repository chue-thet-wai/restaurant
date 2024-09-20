
$(document).ready(function() {
    // Increment button click event
    $('#increment').on('click', function() {
        var $input = $('#seat-count');
        var currentValue = parseInt($input.val(), 10);
        var maxValue = 100; // Set a maximum value if needed

        if (currentValue < maxValue) {
            $input.val(currentValue + 1);
        }
    });

    // Decrement button click event
    $('#decrement').on('click', function() {
        var $input = $('#seat-count');
        var currentValue = parseInt($input.val(), 10);

        if (currentValue > 1) { // Set a minimum value if needed
            $input.val(currentValue - 1);
        }
    });

    $('#menuTab .nav-link').on('click', function(e) {
        e.preventDefault();

        // Get the category ID from the clicked tab
        var categoryId = $(this).data('category');

        // Make an AJAX request to fetch the menu items for the selected category
        loadMenuItems(categoryId);

        $('#menuTab .nav-link').removeClass('active');
                
        $(this).addClass('active');
    });

    // Function to dynamically load menu items
    function loadMenuItems(categoryId) {
        // Use jQuery's $.get method to fetch the menu items from the server
        $.get(`/menu/category/${categoryId}`, function(data) {
            // Clear the current menu content
            $('#menu-content').empty();

            // Populate the new menu items
            $.each(data.menuItems, function(index, item) {
                var menuItem = `
                    <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                        <div class="menu-item text-center">
                            <a href=""><img src="/assets/menu_images/${item.menu_image}" alt="${item.name}" class="menu-img"></a>
                        </div>
                    </div>`;
                $('#menu-content').append(menuItem);
            });
        }).fail(function() {
            console.error('Error fetching menu items');
        });
    }
    
    $('#save-to-gallery').on('click', function() {
        var targetElement = $('#reservation-confirm-card');

        if (targetElement.length) {
            html2canvas(targetElement[0], { useCORS: true, backgroundColor: '#08acbc' }).then(function(canvas) {
                var imgData = canvas.toDataURL('image/png');
                
                var link = document.createElement('a');
                link.href = imgData;
                link.download = 'booking-confirmation.png';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }).catch(function(error) {
                console.error("Error generating canvas:", error);
            });
        } else {
            console.error("Target element not found");
        }
    });

    $('#increment-quantity').on('click', function() {
        var $quantityElement = $('#quantity');
        var quantityValue = parseInt($quantityElement.attr('data-value'));
        quantityValue++;
        $quantityElement.text(quantityValue);
        $quantityElement.attr('data-value', quantityValue);

        // Update total amount based on the new quantity
        var price = parseFloat($('.price').attr('data-value'));
        var totalAmount = price * quantityValue;
        $('#total-amount').text('MMK ' + totalAmount.toFixed(2));
        $('#cart-quantity').text(quantityValue);
    });

    $('#decrement-quantity').on('click', function() {
        var $quantityElement = $('#quantity');
        var quantityValue = parseInt($quantityElement.attr('data-value'));
        if (quantityValue > 1) {  // Ensure quantity doesn't go below 1
            quantityValue--;
            $quantityElement.text(quantityValue);
            $quantityElement.attr('data-value', quantityValue);

            // Update total amount based on the new quantity
            var price = parseFloat($('.price').attr('data-value'));
            var totalAmount = price * quantityValue;
            $('#total-amount').text('MMK ' + totalAmount.toFixed(2));
            $('#cart-quantity').text(quantityValue);
        }
    });
});