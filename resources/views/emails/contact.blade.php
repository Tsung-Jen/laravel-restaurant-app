<!DOCTYPE html>
<html>
<head>
    <title>{{ $contact->subject }}</title>
</head>
<body>
    <h2>Neue Kontaktanfrage</h2>
    <p><strong>Name:</strong> {{ $contact->name }}</p>
    <p><strong>E-Mail:</strong> {{ $contact->email }}</p>
    <p><strong>Betreff:</strong> {{ $contact->subject }}</p>
    <hr>
    <p>{{ $contact->message }}</p>
</body>
</html>
