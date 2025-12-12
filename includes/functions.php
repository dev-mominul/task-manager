<?php
// Function to display success message in a professional tone
function show_success_message($message) {
    if (!empty($message)) {
        echo "<div class='bg-green-500 text-white p-4 rounded-lg mb-6 text-center'>
                <strong>Success!</strong> $message
              </div>";
    }
}
?>
