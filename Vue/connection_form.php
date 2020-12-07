<h4>Connexion :</h4>
<form action='index.php' method='post'>
	<table class="tableConnection">
		<tr>
			<td>
				<input type="text" name="pseudo" class="connectPseudo" required placeholder="Pseudo">
			</td>
		</tr>
		<tr>
			<td>
				<input type="password" name="password" class="connectPassword" required placeholder="Mot de passe">
			</td>
		</tr>
	</table>

	<button type="submit" name="btnConnection" class="valider">Valider</button>
	<button type="reset" class="reset">Effacer</button>
</form>