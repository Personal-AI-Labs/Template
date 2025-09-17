</main>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.js" integrity="sha512-ZHzbWDQKpcZxIT9l5KhcnwQTidZFzwK/c7gpUUsFvGjEsxPusdUCyFxjjpc7e/Wj7vLhfMujNx7COwOmzbn+2w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- JavaScript -->
<script src="/assets/js/main.js"></script>

<?php
// This is the PHP-to-JavaScript bridge for flash messages
if (isset($_SESSION['flash']) && !empty($_SESSION['flash'])) {
    $flashMessages = $_SESSION['flash'];
    unset($_SESSION['flash']); // Clear the message after getting it

    foreach ($flashMessages as $type => $message) {
        // Determine background color based on message type
        $backgroundColor = ($type === 'success')
            ? "linear-gradient(to right, #00b09b, #96c93d)"
            : "linear-gradient(to right, #ff5f6d, #ffc371)";

        // Escape the message for safe use in JavaScript
        $escapedMessage = json_encode($message);

        // Echo the script that will show the toast notification
        echo "<script>
                Toastify({
                    text: {$escapedMessage},
                    duration: 5000,
                    close: true,
                    gravity: 'top', // `top` or `bottom`
                    position: 'right', // `left`, `center` or `right`
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                    style: {
                        background: '{$backgroundColor}',
                    }
                }).showToast();
            </script>";
    }
}
?>
</body>
</html>