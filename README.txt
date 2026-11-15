BUSCADOR DE BIENES RAICES - INSTRUCCIONES

1. Descomprime esta carpeta dentro de C:\xampp\htdocs\.
2. Abre XAMPP Control Panel e inicia Apache. No se necesita MySQL porque los datos vienen del archivo JSON.
3. Abre el navegador y visita: http://localhost/Buscador_Bienes_Raices/
4. Usa Ciudad, Tipo y el rango de precio; pulsa Buscar.
5. Pulsa Mostrar todos para ver los 100 registros del archivo data-1.json.

ARCHIVOS PRINCIPALES
- index.php: muestra el formulario, carga las opciones y presenta los resultados.
- buscador.php: recibe el formulario y carga el buscador.
- funciones.php: contiene funciones para leer el JSON, convertir precios y proteger texto HTML.
- data-1.json: datos originales entregados en Proyecto Base.
- css, js, img y font: recursos originales del Front-End.

GITHUB
Cuando publiques el proyecto, copia la dirección del repositorio en github_url.txt.
El proyecto usa PHP y un archivo JSON; no requiere crear una base de datos MySQL.
