<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats extends CI_Controller {
	var $uid;
	
	public function __construct()
	{
		parent::__construct();
		//$this->load->library('template');
		
		$this->load->model('workout', 'workout');
		$this->load->model('nutrition', 'nutrition');
		$this->load->model('statsm', 'stats_m');
		$this->load->model('badges', 'badges_m');
		
		$this->uid = $this->session ? $this->session->userdata('uid') : "0";
		
	}
	
	public function index()
	{
		
	}

	public function nutrition()
	{
		
	}
	
	public function cal()
	{
		$data = array('result'	=>	'false');
		if (!isset($_GET['date']))
		{
			echo json_encode($data);
			return 0;
		}
		
		//mm/dd/yy
		$date = $_GET['date'];
		
		$nutr_kcal = 0;
		$n_s = $this->stats_m->get_nutrition_cals($date);
		
		foreach ($n_s as $nutr)
		{
			$nutr_kcal += $nutr->n_kcal;
		}
		
		$w_kcal = 0;
		$w_s = $this->stats_m->get_workout_cals($date);
		foreach ($w_s as $w)
		{
			$w_kcal += $w->pa_kcal;
		}
		
		$data['stats'] = array(	'n' =>	$nutr_kcal,
								'w' =>	$w_kcal,
								't' =>	$nutr_kcal - $w_kcal,
								'n_c' => count($n_s),
								'w_c' => count($w_s));
		$data['result'] = 'true';
		echo json_encode($data);
		return 1;
	}
	
	public function badges()
	{
		$data = array('result'	=>	'false');
		
		$u_id = $this->uid;
		if (!isset($u_id))
		{
			echo json_encode($data);
			return 0;
		}
		
		$b = $this->badges_m->getall($u_id);
		$badges = array();
		if (count($b))
		{
			foreach ($b as $badge)
			{
				$b_data = get_object_vars($badge);
				$badges[] = $b_data;
			}
			$data['result'] = 'true';
		} else {
			$data['result'] = 'false';
		}
		$data['badges'] = $badges;
		
		header('Content-type: application/json');
		echo json_encode($data);
		return 1;
	}
	
	public function nutr_info_plank()
	{
		$fid = $_GET['fid'];
		$servings = $_GET['servings'];
		$serving_text = $_GET['serving_text'];
		
		$nutr_data = array();
		$nutr_item_details = $this->stats_m->get_all_nutrients($fid); 
		foreach($nutr_item_details as $nutr)
		{
			$nutr_data[$nutr->NutrDesc] = array(
				'units'	=>	$nutr->Units,
				'val'	=>	$nutr->nvalue*$servings
			);
		}
		
		$plank = '<div class="nutritionInfo">
			<div class="nutrition" style="margin-top:5px;margin-left:5px;width:280px;">
			<table border="0" cellspacing="0" cellpadding="0" width="100%" class="table table-condensed">
				<tbody>
					<tr>
						<th>Nutrition Facts</th>
					</tr>
					<tr>
						<td style="padding: 3px 14px 3px 0;">
							<div class="left_light_label">
								Serving Size
								<div class="holder">';
								if ($serving_text == "1 serving" && $servings == "1" || $serving_text == "1 servings")
								{	
									 $plank .= "1 serving";
								} else {
									 $plank .= $servings." ".$serving_text;
								}
								$plank .= '</div>
							</div>
						</td>
					</tr>
					<tr class="separator">
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="amount">
							Amount Per Serving
						</td>
					</tr>
					<tr>
						<td>
							<div class="line">';
							
								if (isset($nutr_data["Energy"])) {
								$plank .= '<div class="left_strong_label">
									Calories
									<div class="holder">
										'.$nutr_data["Energy"]["val"].'
									</div>
								</div>';
								}
								if (isset($nutr_data["Energy"]) && isset($nutr_data["Total lipid (fat)"])) {
								$plank .= '<div class="right_light_label">
									Calories from Fat
									<div class="holder">
										'. number_format(($nutr_data["Total lipid (fat)"]["val"]*(float)9.091), 0) .'
									</div>
								</div>';
								}
								$plank .= '
							</div>
						</td>
					</tr>
					<tr class="thinSeparator">
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							<div class="small_right_strong_label">
								% Daily Value<sup>*</sup>
							</div>
						</td>
					</tr>';
					if (isset($nutr_data["Total lipid (fat)"])) {
					$plank .= '<tr>
						<td>
							<div class="line">
								<div class="left_strong_label">
									Total Fat
									<div class="holder">
										'.$nutr_data["Total lipid (fat)"]["val"].$nutr_data["Total lipid (fat)"]["units"].'
									</div>
								</div>
								<div class="right_light_label">
									<div class="holder_strong">
										'.number_format(($nutr_data["Total lipid (fat)"]["val"]/(float)65)*100, 0).'%
									</div>
								</div>
							</div>
						</td>
					</tr>';
					}
					if (isset($nutr_data["Fatty acids, total saturated"])) {
					$plank .= '<tr>
						<td class="sub">
							<div class="line">
								<div class="left_light_label">
									Saturated Fat
									<div class="holder">
										'.$nutr_data["Fatty acids, total saturated"]["val"].$nutr_data["Fatty acids, total saturated"]["units"].'
									</div>
								</div>
								<div class="right_light_label">
									<div class="holder_strong">
										'.number_format(($nutr_data["Fatty acids, total saturated"]["val"]/(float)20)*100, 0).'%
									</div>
								</div>
							</div>
						</td>
					</tr>';
					}
					if (isset($nutr_data["Fatty acids, total monounsaturated"])) {
					$plank .= '<tr>
						<td class="sub">
							<div class="line">
								<div class="left_light_label">
									Mono-Unsaturated Fat
									<div class="holder">
										'.$nutr_data["Fatty acids, total monounsaturated"]["val"].$nutr_data["Fatty acids, total monounsaturated"]["units"].'
									</div>
								</div>
							</div>
						</td>
					</tr>';
					}
					if (isset($nutr_data["Fatty acids, total polyunsaturated"])) {
					$plank .= '<tr>
						<td class="sub">
							<div class="line">
								<div class="left_light_label">
									Poly-Unsaturated Fat
									<div class="holder">
										'.$nutr_data["Fatty acids, total polyunsaturated"]["val"].$nutr_data["Fatty acids, total polyunsaturated"]["units"].'
									</div>
								</div>
							</div>
						</td>
					</tr>';
					}
					if (isset($nutr_data["Fatty acids, total trans"])) {
					$plank .= '<tr>
						<td class="sub">
							<div class="line">
								<div class="left_light_label">
									Trans Fat
									<div class="holder">
										'.$nutr_data["Fatty acids, total trans"]["val"].$nutr_data["Fatty acids, total trans"]["units"].'
									</div>
								</div>
							</div>
						</td>
					</tr>';
					}
					if (isset($nutr_data["Fatty acids, total trans-monoenoic"])) {
					$plank .= '<tr>
						<td class="sub">
							<div class="line">
								<div class="left_light_label">
									Trans-Monoenoic Fat
									<div class="holder">
										'.$nutr_data["Fatty acids, total trans-monoenoic"]["val"].$nutr_data["Fatty acids, total trans-monoenoic"]["units"].'
									</div>
								</div>
							</div>
						</td>
					</tr>';
					}
					if (isset($nutr_data["Fatty acids, total trans-polyenoic"])) {
					$plank .= '<tr>
						<td class="sub">
							<div class="line">
								<div class="left_light_label">
									Trans-Polyenoic Fat
									<div class="holder">
										'.$nutr_data["Fatty acids, total trans-polyenoic"]["val"].$nutr_data["Fatty acids, total trans-polyenoic"]["units"].'
									</div>
								</div>
							</div>
						</td>
					</tr>';
					}
					if (isset($nutr_data["Cholesterol"])) {
					$plank .= '<tr>
						<td>
							<div class="line">
								<div class="left_strong_label">
									Cholesterol
									<div class="holder">
										'.$nutr_data["Cholesterol"]["val"].$nutr_data["Cholesterol"]["units"].'
									</div>
								</div>
								<div class="right_light_label">
									<div class="holder_strong">
										'.number_format(($nutr_data["Cholesterol"]["val"]/(float)300)*100, 0).'%
									</div>
								</div>
							</div>
						</td>
					</tr>';
					}
					if (isset($nutr_data["Sodium, Na"])) {
					$plank .= '<tr>
						<td>
							<div class="line">
								<div class="left_strong_label">
									Sodium
									<div class="holder">
										'.$nutr_data["Sodium, Na"]["val"].$nutr_data["Sodium, Na"]["units"].'
									</div>
								</div>
								<div class="right_light_label">
									<div class="holder_strong">
										'.number_format(($nutr_data["Sodium, Na"]["val"]/(float)2400)*100, 0).'%
									</div>
								</div>
							</div>
						</td>
					</tr>';
					}
					if (isset($nutr_data["Potassium, K"])) {
					$plank .= '<tr>
						<td>
							<div class="line">
								<div class="left_strong_label">
									Potassium
									<div class="holder">
										'.$nutr_data["Potassium, K"]["val"].$nutr_data["Potassium, K"]["units"].'
									</div>
								</div>
								<div class="right_light_label">
									<div class="holder_strong">
										'.number_format(($nutr_data["Potassium, K"]["val"]/(float)3500)*100, 0).'%
									</div>
								</div>
							</div>
						</td>
					</tr>';
					}

					if (isset($nutr_data["Carbohydrate, by difference"])) {
					$plank .= '<tr>
						<td>
							<div class="line">
								<div class="left_strong_label">
									Total Carbohydrate
									<div class="holder">
										'.$nutr_data["Carbohydrate, by difference"]["val"].$nutr_data["Carbohydrate, by difference"]["units"].'
									</div>
								</div>
								<div class="right_light_label">
									<div class="holder_strong">
										'.number_format(($nutr_data["Carbohydrate, by difference"]["val"]/(float)300)*100, 0).'%
									</div>
								</div>
							</div>
						</td>
					</tr>';
					}

					if (isset($nutr_data["Fiber, total dietary"])) {
					$plank .= '<tr>
						<td class="sub">
							<div class="line">
								<div class="left_light_label">
									Dietary Fiber
									<div class="holder">
										'.$nutr_data["Fiber, total dietary"]["val"].$nutr_data["Fiber, total dietary"]["units"].'
									</div>
								</div>
								<div class="right_light_label">
									<div class="holder_strong">
										'.number_format(($nutr_data["Fiber, total dietary"]["val"]/(float)25)*100, 0).'%
									</div>
								</div>
							</div>
						</td>
					</tr>';
					}

					if (isset($nutr_data["Sugars, total"])) {
					$plank .= '<tr>
						<td class="sub">
							<div class="line">
								<div class="left_light_label">
									Sugars
									<div class="holder">
										'.$nutr_data["Sugars, total"]["val"].$nutr_data["Sugars, total"]["units"].'
									</div>
								</div>
							</div>
						</td>
					</tr>';
					}

					if (isset($nutr_data["Protein"])) {
					$plank .= '<tr>
						<td>
							<div class="line">
								<div class="left_strong_label">
									Protein
									<div class="holder">
										'.$nutr_data["Protein"]["val"].$nutr_data["Protein"]["units"].'
									</div>
								</div>
								<div class="right_light_label">
									<div class="holder_strong">
										'.number_format(($nutr_data["Protein"]["val"]/(float)50)*100, 0).'%
									</div>
								</div>
							</div>
						</td>
					</tr>';
					}
					$plank .= '<tr>
						<td>
							<div class="line">
							</div>
							&nbsp;
						</td>
					</tr>';
					$plank .= '<tr>
						<td>
							<table border="0" cellspacing="0" cellpadding="0" class="minerals">
								<tbody>
									<tr>
										<td class="left_col">
											Vitamin A ';
											if (isset($nutr_data["Vitamin A, IU"])) {
												$plank .= number_format(($nutr_data["Vitamin A, IU"]["val"]/(float)5000)*100, 0).'%';
											} else if (isset($nutr_data["Vitamin A"])) {
												$plank .= number_format($nutr_data["Vitamin A"]["val"], 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
										<td class="right_col">
											Vitamin C ';
											if (isset($nutr_data["Vitamin C, total ascorbic acid"])) {
												$plank .= number_format(($nutr_data["Vitamin C, total ascorbic acid"]["val"]/(float)60)*100, 0).'%';
											} else if (isset($nutr_data["Vitamin C"])) {
												$plank .= number_format($nutr_data["Vitamin C"]["val"], 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
									</tr><td class="left_col">
											Calcium ';
											if (isset($nutr_data["Calcium, Ca"])) {
												$plank .= number_format(($nutr_data["Calcium, Ca"]["val"]/(float)1000)*100, 0).'%';
											} else if (isset($nutr_data["Calcium"])) {
												$plank .= number_format($nutr_data["Calcium"]["val"], 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
										<td class="right_col">
											Iron ';
											if (isset($nutr_data["Iron, Fe"])) {
												$plank .= number_format(($nutr_data["Iron, Fe"]["val"]/(float)18)*100, 0).'%';
											} else if (isset($nutr_data["Iron"])) {
												$plank .= number_format($nutr_data["Iron"]["val"], 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
									</tr>';
									
									if (isset($nutr_data["Riboflavin"]) || isset($nutr_data["Niacin"])) {
									$plank .= '<tr>
										<td class="left_col">
											Vitamin D ';
											if (isset($nutr_data["Vitamin D"])) {
												$plank .= number_format(($nutr_data["Vitamin D"]["val"]/(float)400)*100, 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
										<td class="right_col">
											Vitamin E ';
											if (isset($nutr_data["Vitamin E (alpha-tocopherol)"])) {
												$plank .= number_format(($nutr_data["Vitamin E (alpha-tocopherol)"]["val"]*1.5/(float)30)*100, 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
									</tr>';
									}
									if (isset($nutr_data["Riboflavin"]) || isset($nutr_data["Niacin"])) {
									$plank .= '<tr>
										<td class="left_col">
											Vitamin K ';
											if (isset($nutr_data["Vitamin K (phylloquinone)"])) {
												$plank .= number_format(($nutr_data["Vitamin K (phylloquinone)"]["val"]/(float)80)*100, 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
										<td class="right_col">
											Thiamin ';
											if (isset($nutr_data["Thiamin"])) {
												$plank .= number_format(($nutr_data["Thiamin"]["val"]/(float)1.5)*100, 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
									</tr>';
									}
									if (isset($nutr_data["Riboflavin"]) || isset($nutr_data["Niacin"])) {
									$plank .= '<tr>
										<td class="left_col">
											Riboflavin ';
											if (isset($nutr_data["Riboflavin"])) {
												$plank .= number_format(($nutr_data["Riboflavin"]["val"]/(float)1.7)*100, 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
										<td class="right_col">
											Niacin ';
											if (isset($nutr_data["Niacin"])) {
												$plank .= number_format(($nutr_data["Niacin"]["val"]/(float)20)*100, 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
									</tr>';
									}
									if (isset($nutr_data["Vitamin B-12"]) || isset($nutr_data["Vitamin B-6"])) {
									$plank .= '<tr>
										<td class="left_col">
											Vitamin B-6 ';
											if (isset($nutr_data["Vitamin B-6"])) {
												$plank .= number_format(($nutr_data["Vitamin B-6"]["val"]/(float)2.0)*100, 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
										<td class="right_col">
											Vitamin B-12 ';
											if (isset($nutr_data["Vitamin B-12"])) {
												$plank .= number_format(($nutr_data["Vitamin B-12"]["val"]/(float)6)*100, 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
									</tr>';
									}
									if (isset($nutr_data["Folate, total"]) || isset($nutr_data["Biotin"])) {
									$plank .= '<tr>
										<td class="left_col">
											Folate ';
											if (isset($nutr_data["Folate, total"])) {
												$plank .= number_format(($nutr_data["Folate, total"]["val"]/(float)400)*100, 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
										<td class="right_col">
											Biotin ';
											if (isset($nutr_data["Biotin"])) {
												$plank .= number_format(($nutr_data["Biotin"]["val"]/(float)300)*100, 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
									</tr>';
									}
									if (isset($nutr_data["Phosphorus, P"]) || isset($nutr_data["Pantothenic acid"])) {
											$plank .= '<tr>
										<td class="left_col">
											Pantothenic Acid ';
											if (isset($nutr_data["Pantothenic acid"])) {
												$plank .= number_format(($nutr_data["Pantothenic acid"]["val"]/(float)10)*100, 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
										<td class="right_col">
											Phosphorus ';
											if (isset($nutr_data["Phosphorus, P"])) {
												$plank .= number_format(($nutr_data["Phosphorus, P"]["val"]/(float)1000)*100, 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
									</tr>';
									}
									if (isset($nutr_data["Zinc, Zn"]) || isset($nutr_data["Magnesium, Mg"])) {
											$plank .= '<tr>
										<td class="left_col">
											Magnesium ';
											if (isset($nutr_data["Magnesium, Mg"])) {
												$plank .= number_format(($nutr_data["Magnesium, Mg"]["val"]/(float)400)*100, 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
										<td class="right_col">
											Zinc ';
											if (isset($nutr_data["Zinc, Zn"])) {
												$plank .= number_format(($nutr_data["Zinc, Zn"]["val"]/(float)15)*100, 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
									</tr>';
									}
									if (isset($nutr_data["Selenium, Se"]) || isset($nutr_data["Copper, Cu"])) {
											$plank .= '<tr>
										<td class="left_col">
											Selenium ';
											if (isset($nutr_data["Selenium, Se"])) {
												$plank .= number_format(($nutr_data["Selenium, Se"]["val"]/(float)70)*100, 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
										<td class="right_col">
											Copper ';
											if (isset($nutr_data["Copper, Cu"])) {
												$plank .= number_format(($nutr_data["Copper, Cu"]["val"]/(float)2.0)*100, 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
									</tr>';
									}
									if (isset($nutr_data["Manganese, Mn"])) {
									$plank .= '<tr>
										<td class="left_col">
											Manganese ';
											if (isset($nutr_data["Manganese, Mn"])) {
												$plank .= number_format(($nutr_data["Manganese, Mn"]["val"]/(float)2.0)*100, 0).'%';
											} else { 
												$plank .= "0%";
											}
										$plank .= '</td>
									</tr>';
									}
									$plank .= '
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			</div>
		</div>
		';
		
		echo $plank;
		
	}

	
}
