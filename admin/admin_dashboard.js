function approveProperty(propertyID) {
    $.ajax({
        url: 'admin_approve.php',
        type: 'POST',
        data: { property_id: propertyID, action: 'approve' },
        success: function(response) {
            alert('Property approved successfully!');
            location.reload();
        },
        error: function(xhr, status, error) {
            alert('Failed to approve property. Please try again.');
        }
    });
}

function rejectProperty(propertyID) {
    $.ajax({
        url: 'admin_approve.php',
        type: 'POST',
        data: { property_id: propertyID, action: 'deny' },
        success: function(response) {
            alert('Property denied successfully!');
            location.reload();
        },
        error: function(xhr, status, error) {
            alert('Failed to deny property. Please try again.');
        }
    });
}
