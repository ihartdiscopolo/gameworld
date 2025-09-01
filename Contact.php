<?php 
require("Functions.php");
HtmlHead();
?>

<main class="banner">
    <h2>Contact</h2>
    <p>I'd love to hear from you! Use the form below to get in touch.</p>
    <form action="#" method="get" onsubmit="alert('Thanks for your message! (Form not actually submitted)'); return false;">
        <label for="first-name">First Name:</label>
        <input type="text" id="first-name" name="first_name" placeholder="First Name" required>
        
        <label for="last-name">Last Name:</label>
        <input type="text" id="last-name" name="last_name" placeholder="Last Name" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Your Email" required>
        <br>
        
        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="5" placeholder="Your Message" required></textarea>
        <br>
        
        <button type="submit">Send Message</button>
        <button type="reset">Reset</button>
    </form>
</main>

<?php
HtmlFooter();
?>