<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JAKE</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Fuente moderna -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body class="min-h-screen bg-cover bg-center bg-no-repeat font-[Poppins] relative"
      style="background-image: url('{{ asset('images/fondo.png') }}');">

  <!-- Overlay oscuro -->
  <div class="absolute inset-0 bg-black/60"></div>

  <!-- NAVBAR -->
  <header class="relative z-10 flex justify-between items-center px-8 py-5">

    <!-- Logo -->
 <div class="flex items-center">
      <div class="backdrop-blur-sm p-2 rounded-xl shadow-lg">
        <img src="{{ asset('images/Logo_grisclaro.png')}}" alt="Logo" class="h-14">
      </div>
 </div>

    <!-- Links -->
    <nav class="flex items-center space-x-6 text-white font-medium text-lg">

      <a href="{{ route('register') }}" 
         class="hover:underline transition flex items-center space-x-2">
        <i class="fas fa-user-plus"></i>
        <span>Registro</span>
      </a>

      <span class="opacity-50">|</span>

      <a href="{{ route('login') }}" 
         class="hover:underline transition flex items-center space-x-2">
        <i class="fas fa-sign-in-alt"></i>
        <span>Iniciar sesión</span>
      </a>

    </nav>
  </header>

  <!-- CONTENIDO CENTRAL -->
  <main class="relative z-10 flex flex-col justify-center items-center text-center h-[80vh] px-6 text-white">

    <!-- Título -->
    <h1 class="text-7xl md:text-8xl lg:text-9xl font-extrabold mb-6 tracking-widest drop-shadow-2xl">
      JAKE
    </h1>

    <!-- Descripción -->
    <p class="max-w-4xl text-xl md:text-2xl leading-relaxed text-gray-200 font-light">
      Este sistema ha sido diseñado para organizar actividades de manera eficiente,
      permitiendo programar tareas, gestionar materiales, llevar control de asistencia
      y administrar recursos de forma sencilla. Además, cuenta con funcionalidades
      para facilitar reemplazos en caso de que algún responsable no pueda cumplir
      con su asignación.
    </p>

  </main>

</body>
</html>