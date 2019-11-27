<?php

use Illuminate\Support\Facades\Auth;

?>
<nav class="navbar navbar-expand-md navbar-dark gen_nav" style="background-color: #15C5C5">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/brand/logo.jpg') }}" height="40" alt="logo">
            <span>{{ config('app.name', 'BlemaDemo') }}</span>
        </a>

        <!-- Toggler/collapsibe Button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav ml-auto">
                @if(Auth::check())
                    <li class="nav-item {{ Route::currentRouteNamed('home') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('home') }}">Dashboard</a>
                    </li>
                @endif
                <li class="nav-item dropdown">
                    <a id="catDropdown1" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        Categories <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="catDropdown1">
                        <a class="dropdown-item" href="{{ route('foodstuff') }}">Raw Food Stuff</a>
                        <a class="dropdown-item" href="{{ route('soup_recipes') }}">Soup Recipes</a>
                        <a class="dropdown-item" href="{{ route('proteins') }}">Fish and Meat</a>
                        <a class="dropdown-item" href="{{ route('fruits_vegs') }}">Fruits & Vegetables</a>
                        <a class="dropdown-item" href="{{ route('herbs_spices') }}">Herbs & Spices</a>
                        <a class="dropdown-item" href="{{ route('coffee_tea') }}">Coffee & Tea</a>
                        <a class="dropdown-item" href="{{ route('drinks') }}">Beverages, Drinks & Water</a>
                        <a class="dropdown-item" href="{{ route('breakfast_cereal') }}">Breakfast & Cereal</a>
                        <a class="dropdown-item" href="{{ route('eggs_dairy') }}">Eggs & Dairy</a>
                        <a class="dropdown-item" href="{{ route('toiletries') }}">Toiletries</a>
                        <a class="dropdown-item" href="{{ route('household') }}">Household items</a>
                        <a class="dropdown-item" href="{{ route('groceries') }}">Groceries & Staples</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a id="servicesDropdown1" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        Kitchen and Bakes <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="servicesDropdown1">
                        <a class="dropdown-item" href="{{ route('kitchen') }}">Blema Kitchen</a>
                        <a class="dropdown-item" href="#">Bakery</a>
                        <a class="dropdown-item" href="#">Fried Snacks</a>
                    </div>
                </li>

                <!-- Authentication Links -->
                @guest
                    <li class="nav-item mr-3 {{ \Request::is('about_us') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('about_us') }}">Who we are</a>
                    </li>
                    <li class="nav-item mr-3 {{ \Request::is('contact_us') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('contact_us') }}">Contact Us</a>
                    </li>
                    <li class="nav-item mr-3 {{ \Request::is('my_cart') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('cart') }}">My Cart</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item mr-3 {{ \Request::is('special_order') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('special.order') }}">Special Order</a>
                    </li>

                    <li class="nav-item mr-3 {{ \Request::is('my_cart') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('cart') }}">My Cart</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown1" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown1">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form1').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form1" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
