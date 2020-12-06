<form method="post" action="index.php">
	<table class="tableInscription">
		<tr>
			<td>
				<input type="text" name="pseudo" class="inscriptionPseudo" placeholder="Pseudo" required>
			</td>
		</tr>
		<tr>
			<td>
				<input type="password" name="password" class="inscriptionPassword" placeholder="Mot de passe" required>
			</td>
		</tr>
		<tr>
			<td>
				<input type="text" name="money" class="inscriptionMoney" placeholder="Argent" required>
			</td>
		</tr>
	</table>

	<button type="submit" name="btnInscription" class="valider">Valider</button>
	<button type="reset" class="reset">Effacer</button>
</form>