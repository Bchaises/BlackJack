<form method="POST" action="index.php" name="formChoice" class="formChoice">
	<table class="tableChoice">
		<tr class="trTableChoice1">
			<td>
				<input type="radio" name="radioChoice" id="draw" value="draw" class="drawCard" required>
				<label name="drawCard" for="draw">Tirer une carte	</label>
			</td>

			<td>
				<input type="radio" name="radioChoice" id="past" value="past" class="past" required>	
				<label name="past" for="past">Passer la main</label>
			</td>
		</tr>
		<tr class="trTableChoice2">
			<td>
				<input type="radio" name="radioChoice" id="double" value="double" class="double" required>
				<label name="double" for="double">Doubler la mise</label>
			</td>

			<td>
				<input type="radio" name="radioChoice" id="doubleGame" value="doubleGame" class="doubleGame" required>
				<label name="doubleGame" for="doubleGame">Double son jeu</label>
			</td>
		</tr>
	</table>
	<button class="buttonChoiceSubmit" name="btnChoiceSubmit">Valider</button>
</form>