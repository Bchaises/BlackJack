<p>Pour pouvoir jouer vous devez <br>
dans un premier temps renseigner votre mise.</p>
<table class="tableMise">
	<tr>
		<td>
			<?= 'Solde actuel : <span class="solde">'.htmlspecialchars($_SESSION['money']).'€</span>';?>
		</td>
	</tr>
<form method="POST" action="index.php">
	<tr>
		<td>
			<input type="text" name="mise" class="inputMise" placeholder="Mise" required>
			<button type="submit" name="btnMise" class="valider">Valider</button>
		</td>
	</tr>
</table>
</form>