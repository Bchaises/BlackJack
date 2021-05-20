<div id="containerChoice">
	<form method="POST" action="index.php" name="formChoice" id="formChoice">

		<div id="choice_item_1">

			<input type="radio" name="radioChoice" id="draw" value="draw" class="drawCard" arial-label="Vous piochez une carte" required>
			<label name="drawCard"  for="draw" style="padding-right: 5px;">Tirer une carte</label>

			<input type="radio" arial-label="C'est au tour du croupiez et la partie se termine"  name="radioChoice" id="past" value="past" class="past" required>	
			<label name="past" for="past">Passer la main</label>

		</div>

		<div id="choice_item_2">

			<input type="radio" name="radioChoice" id="double" value="double" class="double" arial-label="Vous piochez une carte et doublez la mise" required>
			<label name="double"   for="double" style="padding-right: 5px;">Doubler la mise</label>

			<input type="radio" name="radioChoice" id="doubleGame" value="doubleGame" class="doubleGame" arial-label="si vous avez deux cartes similaires vous divisez votre paquet ainsi que votre mise" required>
			<label name="doubleGame"   for="doubleGame">Double son jeu</label>

		</div>

		<div id="choice_item_3">
			<button name="btnChoiceSubmit" id="btnChoiceSubmit">Valider</button>
		</div>
	</form>
</div>