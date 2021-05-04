<p style="text-align: center;">Pour pouvoir jouer vous devez <br>
dans un premier temps renseigner votre mise.</p>

<div id="formMise">
	<p>Solde actuel : <span id="solde"><?= $_SESSION['money']?>€</span></p>

	<form method="POST" action="index.php">

		<input type="text" name="mise" class="inputMise" placeholder="Mise (2€ - 100€)" required>
		<button type="submit" name="btnMise" id="btnMise">Valider</button>

	</form>

</div>