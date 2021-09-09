<html>
<head>
    <title>AutSecurity</title>
</head>
<body>
<form method="POST" action="enviar-dados">
    @csrf
    <label for="valor">Informe o valor</label>
    <input type="text" id="valor" name="valor"/>
    <button type="submit">Enviar</button>
</form>
</body>
</html>
