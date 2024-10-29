<?php
session_start()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire fiche frais</title>
</head>
<body>
    <h1>Nouvelle fiche de frais</h1>
    <form action="ajoutFrais.php" method="post">
        <label>Type de frais:</label><br>
        <label>Montant repas (en €):</label>
        <input type="text" inputmode="numeric" pattern="[0-9]*" name="montant_repas" oninput="validateNumberInput(this); calculateTotal()" placeholder="0" min="0"><br>
        <label>Montant hébergement (en €):</label>
        <input type="text" inputmode="numeric" pattern="[0-9]*" name="montant_hebergement" oninput="validateNumberInput(this);calculateTotal()" placeholder="0" min="0"><br>
        <label>Montant déplacement (en €):</label>
        <input type="text" inputmode="numeric" pattern="[0-9]*" name="montant_deplacement" oninput="validateNumberInput(this);calculateTotal()" placeholder="0" min="0"><br>
        <label>Total (en €):</label>
        <input type="text" id="total" name="total" readonly required>
        <label>Date:</label>
        <input type="date" id="date" name="date" required>
        <input type="submit" value="Enregistrer">
    </form>

    <script>
        function validateNumberInput(input) {
        input.value = input.value.replace(/[^0-9.]/g, ''); 
    }

        function showAmountField(fieldId) {
            document.querySelectorAll('.amount-field').forEach(field => field.style.display = 'none');
            
            document.getElementById(fieldId).style.display = 'block';
        }

        function calculateTotal() {
            const montantRepas = parseFloat(document.querySelector('input[name="montant_repas"]').value) || 0;
            const montantHebergement = parseFloat(document.querySelector('input[name="montant_hebergement"]').value) || 0;
            const montantDeplacement = parseFloat(document.querySelector('input[name="montant_deplacement"]').value) || 0;

            const total = montantRepas + montantHebergement + montantDeplacement;

            document.getElementById('total').value = total.toFixed(2);
        }   

        const today = new Date();
        const maxDate = today.toISOString().split("T")[0];
        
        const lastYear = new Date();
        lastYear.setFullYear(today.getFullYear() - 1);
        const minDate = lastYear.toISOString().split("T")[0];

        document.getElementById("date").setAttribute("min", minDate);
        document.getElementById("date").setAttribute("max", maxDate);
    </script>
</body>
</html>