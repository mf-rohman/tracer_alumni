<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Card dengan Tombol Kabur</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>

  <body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="max-w-sm bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 p-6 text-center">
      <img
        src="https://images.unsplash.com/photo-1586996292898-71f4036c4e07?q=80&w=2670&auto=format&fit=crop"
        alt="Random"
        class="rounded-lg mb-4 object-cover h-48 w-full"
      />
      <h2 class="text-2xl font-bold mb-2 text-gray-800">Pilih Option Di Bawah</h2>
      <p class="text-gray-600 mb-6">
        Silahkan Login jika sudah punya akun dan Register jika belum punya akun
      </p>

        <div class="flex justify-center gap-4 relative">

          <button
            onclick="window.location='/'"
            class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300"
          >
            Login
          </button>


          <button
            onclick="window.location='/'"
            id="tombol-kabur"
            class="px-5 py-2 bg-red-600 text-white rounded-lg transition-transform duration-200"
          >
           Register
          </button>
        </div>
    </div>

    <script>
      const tombolKabur = document.getElementById("tombol-kabur");

      tombolKabur.addEventListener("mouseenter", () => {
        

        const randomX = Math.floor(Math.random() * 400 - 400);
        const randomY = Math.floor(Math.random() * 400 - 400);
        tombolKabur.style.transform = `translate(${randomX}px, ${randomY}px)`;
      });

      tombolKabur.addEventListener("mouseleave", () => {
        tombolKabur.style.transform = "translate(0, 0)";
      });
    </script>
  </body>
</html>
