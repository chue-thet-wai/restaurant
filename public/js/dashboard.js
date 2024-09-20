$(document).ready(function() {
    fetchPurchaseHistory('premium', '/premium-purchase-history', '#premium-purchase-history-body');
    fetchPurchaseHistory('point', '/point-purchase-history', '#point-purchase-history-body');

    function fetchPurchaseHistory(type, url, tableBodySelector) {
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                //console.log('Data:', data); 
                populateTable(tableBodySelector, data);
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr.responseText);
            }
        });
    }

    function populateTable(tableBodySelector, purchases) {
        const $tableBody = $(tableBodySelector);
        $tableBody.empty();

        purchases.forEach(purchase => {
            const row = `
                <tr>
                    <td>${purchase.transaction_id}</td>
                    <td>${purchase.name}</td>
                    <td>${new Date(purchase.created_at).toISOString().split('T')[0]}</td>
                    <td>${purchase.amount}</td>
                </tr>
            `;
            $tableBody.append(row);
        });
    }
});
