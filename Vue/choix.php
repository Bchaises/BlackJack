<form method="POST" action="index.php" name="formChoice" class="formChoice">
	<table class="tableChoice">
		<tr class="trTableChoice1">
			<td>
				<input type="radio" name="radioChoice" class="drawCard">
				<label name="drawCard">Tirer une carte</label>
			</td>

			<td>
				<input type="radio" name="radioChoice" class="past">	
				<label name="past">Passer la main</label>
			</td>
		</tr>
		<tr class="trTableChoice2">
			<td>
				<input type="radio" name="radioChoice" class="double">
				<label name="double">Doubler la mise</label>
			</td>

			<td>
				<input type="radio" name="radioChoice" class="doubleGame">
				<label name="doubleGame">Double son jeu</label>
			</td>
		</tr>


	</table>
	<button class="buttonChoiceSubmit">Valider</button>
</form>