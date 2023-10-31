<?php
dump('welcome-unauthenticated');
?>
<!doctype html>
<html x-data="{ darkMode: localStorage.getItem('dark') === 'true'}"
			x-init="$watch('darkMode', val => localStorage.setItem('dark', val))"
			x-bind:class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 dark:bg-gray-800">
    <x-header-unauthenticated/>
    <div class="bg-gray-50 dark:bg-gray-900">

        <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 dark:bg-gray-900">
            <main>
                <!--slot for content -->
                <section class="bg-white dark:bg-gray-900">
                  <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
                      <div class="mr-auto place-self-center lg:col-span-7">
                          <h1 class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white">{{ __('Helping you locate your child safely')}}</h1>
                          <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">Picture a friendly system that tracks student attendance and keeps parents and teachers in the loop through SMS, email, or push notifications. It's like a watchful friend, ensuring everyone stays informed and making attendance reports a breeze.</p>
                          <a href="#" class="inline-flex items-center justify-center px-5 py-3 mr-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">
                              Get started
                              <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                          </a>
                          <a href="#" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                              Speak to Sales
                          </a>
                      </div>
                      <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
                          <img src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/hero/phone-mockup.png" alt="mockup">
                      </div>
                  </div>
              </section>

            </main>

            <x-footer />
          </div>
    </div>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="https://flowbite-admin-dashboard.vercel.app//app.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.2/datepicker.min.js"></script>
</body>

</html>