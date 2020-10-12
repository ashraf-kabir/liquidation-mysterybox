<html>
    <head>
        <title>Contact Us</title>
    </head>
    <body>
        <form method="POST">
            <label for="email">Your Email</label><br>
            <input type="email" name='email' required><br>
            <label for="name">Name</label><br>
            <input type="text" name='name' required><br>
            <label for="name">Message</label><br>
            <input type="hidden" name='h_pot'>
            <textarea name="message" id="" cols="30" rows="10" required></textarea><br>
            <input type="submit" name='btn-send-contact'>
        </form>
    </body>
</html>