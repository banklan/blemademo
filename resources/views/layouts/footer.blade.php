<div class="footer">
    <div class="footer_wrapper">
        <div class="about">
            <h5 class="">About</h5>
            <ul>
                <li><a href="{{ route('about_us') }}">About Us</a></li>
                <li><a href="{{ route('foodstuff') }}">Order Foodstuffs</a></li>
                <li><a href="{{ route('special.order') }}">Special Order</a></li>
                <li><a href="{{ route('kitchen') }}">Order cooked meal</a></li>
                <li><a href="{{ route('how_it_works') }}">How it works</a></li>
                <li><a href="{{ route('terms') }}">Our Terms and Conditions</a></li>
            </ul>
        </div>
        <div class="contact">
            <h5 class="">Contact/Login</h5>
            <ul>
                <li><a href="">Login</a></li>
                <li><a href="">Sign Up</a></li>
                <li><a href=" {{ route('contact_us') }}">Contact US</a></li>
                <li><i class="fas fa-phone"></i>&nbsp; 080</li>
                <li><i class="fas fa-envelope"></i>&nbsp; info@blemagroceries.com</li>
            </ul>
        </div>
        <div class="socials">
            <a href=""><i class="fab fa-facebook-square"></i></a>  <a href=""><i class="fab fa-twitter-square"></i></a>
            <a href=""><i class="fab fa-instagram"></i></a>
            <p>&copy; <?php echo date('Y'); ?> &nbsp; blemagroceries.com &nbsp; All rights reserved
        </div>
    </div>
    <div class="footer_base">
        Developed by VidSpectra Resources..08023572852
    </div>
</div>
