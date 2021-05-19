

<div class="containerProfil">


	<div class="container_history">

		<div id="pagination">
			<div id="number">
				<ul>
					<li id="Previous">
						<a href="index.php?page=<?=$Previous;?>" arial-label="Previous">
							<span>« Previous</span>
						</a>
					</li>
					<?php for($i = 1;$i <= $pages; $i++ )
						echo '<li class="items"><a href="index.php?page='.$i.'" >'.$i.'</a></li>'
					?>

					<li id="Next">
						<a href="index.php?page=<?=$Next;?>" arial-label="Next" id="Next">
							<span>Next »</span>
						</a>
					</li>
				</ul>
			</div>

			<div id="formLimit">
				<form action="#" method="post">
					<select name="limit-records" id="limit-records">
						<option disabled="disabled" selected="selected">
							---Limit Records---
						</option>
						<?php foreach([10,25,50,100] as $limit){

							echo '<option value="'.$limit.'">'.$limit.'</option>';
						}

						?>
					</select>
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
			<p>Solde : <?=$_SESSION['money'];?>€</p>
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