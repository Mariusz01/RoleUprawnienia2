<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nowy użytkownik zarejestrowany</title>
</head>
<body>
    <p>Witaj {{ $user->name }},</p>

    <p>Dziękujemy za rejestrację! Twój profil został pomyślnie utworzony.</p>

    <p>Zaloguj się na swoje konto, korzystając z poniższych danych:</p>

    <ul>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Hasło:</strong> (Twoje hasło)</li>
    </ul>

    <p>Pozdrawiamy,</p>
    <p>Zespół Twojej Strony</p>
</body>
</html>
