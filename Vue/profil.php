

<div class="containerProfil">


	<div class="container_history">

		<div id="pagination">
			<div id="number">
				<ul>
					<div>
						<li id="Previous">
							<a href="index.php?page=<?=$Previous;?>" arial-label="Previous">
								<span>« Previous</span>
							</a>
						</li>
					</div>
					<?php for($i = 1;$i <= $pages; $i++ )
						echo '<div><li class="items"><a href="index.php?page='.$i.'" >'.$i.'</a></li></div>'
					?>
					<div>
						<li id="Next">
							<a href="index.php?page=<?=$Next;?>" arial-label="Next" id="Next">
								<span>Next »</span>
							</a>
						</li>
					<div>
				</ul>
			</div>

			<div id="formLimit">
				<form action="#" method="post">
					<div>
						<select name="limit-records" id="limit-records">
							<option disabled="disabled" selected="selected">
								<?php

								if (isset($_SESSION['limit-records'])) {
									echo $_SESSION['limit-records'];
								}
								else{
									echo "--Limite--";
								}


								?>
							</option>
							<?php foreach([10,25,50,100] as $limit){

								echo '<option value="'.$limit.'">'.$limit.'</option>';
							}

							?>
						</select>

						<select name="variation" id="variation">
							<option disabled="disabled" selected="selected">
								<?php

								if (isset($_SESSION['variation'])) {
									echo $_SESSION['variation'];
								}
								else{
									echo "--Variation--";
								}


								?>
							</option>

							<option value="croissant">croissant</option>
							<option value="décroissant">décroissant</option>
						</select>
					</div>
				</form>
			</div>
		</div>
		<div class="history">
			<table class="tableHistory">
				<tr class="row_history">
					<th class="title_history">ID</th>
					<th class="title_history">Pari</th>
					<th class="title_history">Profit</th>
					<th class="title_history">Victoire/Défaite</th>
					<th class="title_history">Date</th>
				</tr>
				<?= 
					$parties
				?>

			</table>
		</div>
	</div>

	<div id="userInfo">
		<div class="win_rate">
			<p>Pourcentage de victoire</p>
			<div class="circle">
				<?=$winRate?>
			</div>
		</div>

		<div class="solde">
			<p style="padding-right: 10px;border-right:1px solid black">Solde : <?=$_SESSION['money'];?>€</p>
			<p style="padding-left: 10px;">Rang : #<?=$rang;?></p>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#limit-records").change(function(){
			$('form').submit();
		})
	})
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$("#variation").change(function(){
			$('form').submit();
		})
	})
</script>