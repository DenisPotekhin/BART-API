<p align="center">
    <h1 align="center">REST Backend API</h1>
    <h3 align="center">According to specification http://api.programator.sk/</h3>
    <br>
</p>




USAGE
------------

<ul>
<li>git clone https://github.com/DenisPotekhin/BART-API.git</li>
<li>composer update</li>
<li>create database and create tables from loq.sql</li>
<li>edit the file `config/db.php` with real data</li>
<li>see examples in 'examples' folder</li>
<li>send requests</li>
</ul>


API supports the following request types
------------

<ul>
<li>GET /galleries: index all galleries with images;</li>
<li>POST /galleries ('name' = gallery name): create new gallery;</li>
<li>GET /galleries/{path}: index gallery with images in gallery;</li>
<li>POST /galleries/{path}  (key = 'image', value = file): upload image in gallery;</li>
<li>DELETE /galleries/{path}: delete gallery with name = 'path';</li>
<li>DELETE /galleries/{path}/{filename}: delete image in gallery 'path' with name = 'filename';</li>
<li>GET /images/{width}x{height}/{path}/{name}: preview image generation, with width and height;</li>
</ul>