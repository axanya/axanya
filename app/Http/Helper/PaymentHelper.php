<?php

/**
 * Payment Helper
 *
 * @package     Makent
 * @subpackage  Helper
 * @category    Helper
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Helper;

use App\Models\AppliedTravelCredit;
use App\Models\Calendar;
use App\Models\CouponCode;
use App\Models\Currency;
use App\Models\Fees;
use App\Models\HostPenalty;
use App\Models\Referrals;
use App\Models\Reservation;
use App\Models\Rooms;
use App\Models\SpecialOffer;
use DateTime;
use Session;

class PaymentHelper {
	/**
	 * Common Function for Price Calculation
	 *
	 * @param int $room_id   Room Id
	 * @param int $checkin   CheckIn Date
	 * @param int $checkout   CheckOut Date
	 * @param int $guest_count   Guest Count
	 * @param int $special_offer_id   Special Offer Id (Optional)
	 * @param int $change_reservation   Dummy string to identify Change reservation or not (Optional)
	 * @return json   Calculation Result
	 */
	public function price_calculation($room_id, $checkin, $checkout, $guest_count, $special_offer_id = '', $change_reservation = '') {
		$from = new DateTime($checkin);
		$to = new DateTime($checkout);
		$total_nights = $to->diff($from)->format("%a");
		$date1 = date('Y-m-d', strtotime($checkin));
		$enddate = date('Y-m-d', strtotime($checkout));
		$date2 = date('Y-m-d', (strtotime('-1 day', strtotime($enddate))));
		$days = $this->get_days($date1, $date2);
		$calendar_result = Calendar::where(['room_id' => $room_id])->whereIn('date', $days)->get();
		$rooms_details = Rooms::find($room_id);
		$price_details = $rooms_details->rooms_price;
		$price = $price_details->night;
		$special_nights_price = 0;
		$i = 0;
		$special_nights_count = 0;
		$special_night_status = '';
		$week_end_price = 0;
		$total_week_price = 0;
		$month_price_days = 0;
		$total_month_price = 0;
		$week_end_days = 0;
		$percentage = Fees::find(1)->value;
		$host_fee_percentage = Fees::find(2)->value;
		$result['additional_guest'] = 0;
		$result['security_fee'] = 0;
		$result['cleaning_fee'] = 0;
		$friday = 0;
		$satday = 0;
		$satday_count = 0;
		$friday_count = 0;
		$special_week_end_days = 0;
		$result['coupon_amount'] = 0;

		$reservation_change = Reservation::where(['code' => $change_reservation])->get();

		foreach ($reservation_change as $key) {
			$reservation_checkin = $key->checkin;
			$reservation_checkout = $key->checkout;
		}

		if ($change_reservation) {
			$reservation_checkin = date('Y-m-d', strtotime($reservation_checkin));
			$reserve_date = date('Y-m-d', strtotime($reservation_checkout));
			$reservation_checkout = date('Y-m-d', (strtotime('-1 day', strtotime($reserve_date))));
			$reseved_days = $this->get_days($reservation_checkin, $reservation_checkout);
		}

		foreach ($calendar_result as $key) {
			$status = $key->status;

			if ($status == "Not available") {
				if ($change_reservation) {
					$result['status'] = "Not available";
					$datecheck = in_array($key->date, $reseved_days);

					if (!$datecheck) {
						return json_encode($result);
					}
				} else {
					$result['status'] = "Not available";
					return json_encode($result);
				}
			} else if ($status == "Available") {
				$special_nights_price += $key->price;
				$special_nights_count += 1;
				$special_night_status = "Available";
				$i++;
			}

			if (date('N', strtotime($key->date)) == 4) {
				if ($price_details->thursday == 'Yes') {
					$friday_count = 1 + $friday;
					$friday++;
				}
			}

			if (date('N', strtotime($key->date)) == 5) {
				if ($price_details->friday == 'Yes') {
					$friday_count = 1 + $friday;
					$friday++;
				}
			}

			if (date('N', strtotime($key->date)) == 6) {
				if ($price_details->saturday == 'Yes') {
					$satday_count = 1 + $satday;
					$satday++;
				}
			}
		}

		$special_week_end_days = $friday_count + $satday_count;
		$month_days = floor($total_nights / 28);
		$month_days_remaining = number_format($total_nights % 28);
		$week_days = floor($month_days_remaining / 7);
		$week_days_remaining = number_format($month_days_remaining % 7);

		if ($special_night_status == "Available") {
			if ($price_details->weekend != 0) {
				$friday = 0;
				$satday = 0;
				$satday_count = 0;
				$friday_count = 0;

				for ($i = strtotime($checkin); $i < strtotime($checkout); $i = strtotime('+1 day', $i)) {
					if (date('N', $i) == 4) {
						if ($price_details->thursday == 'Yes') {
							$friday_count = 1 + $friday;
							$friday++;
						}
					}
					if (date('N', $i) == 5) {
						if ($price_details->friday == 'Yes') {
							$friday_count = 1 + $friday;
							$friday++;
						}
					}
					if (date('N', $i) == 6) {
						if ($price_details->saturday == 'Yes') {
							$satday_count = 1 + $satday;
							$satday++;
						}
					}
				}

				$week_end_days = ($friday_count + $satday_count) - $special_week_end_days;
				$week_end_remainig_nights = $total_nights - $week_end_days;
				$week_end_price = round($price_details->weekend * $week_end_days);
			}

			if ($week_end_days) {
				$night_diff = $week_end_remainig_nights - $special_nights_count;
			} else {
				$night_diff = $total_nights - $special_nights_count;
			}

			if ($month_days) {
				if ($price_details->month != 0) {
					$month_days_round = floor($night_diff / 28);
					$remaing_month_days = number_format($night_diff % 28);
					$average_month_price = round($price_details->month / 28);
					$round_month_price = round($month_days_round * $price_details->month);

					if ($price_details->weekend != 0) {
						$remaing_days_price = round(($remaing_month_days * $average_month_price) + $round_month_price);
						$total_month_price = round($remaing_days_price + $week_end_price + $special_nights_price);
					} else {
						$remaing_days_price = round(($remaing_month_days * $average_month_price) + $round_month_price);
						$total_month_price = round($remaing_days_price);
					}
				} else {
					$month_price = round($night_diff * $price_details->night);
					$total_month_price = round($month_price + $week_end_price + $special_nights_price);
				}
			}

			if ($week_days != 0 && $month_days == 0) {
				if ($price_details->week != 0) {
					$week_days_round = floor($night_diff / 7);
					$remaing_week_days = number_format($night_diff % 7);
					$average_week_price = round($price_details->week / 7);
					$round_week_price = round($week_days_round * $price_details->week);
					$remaing_days_price = round(($remaing_week_days * $average_week_price) + $round_week_price);
					$total_week_price = round($remaing_days_price + $week_end_price + $special_nights_price);
				} else {
					$week_price = round($night_diff * $price_details->night);
					$total_week_price = round($week_price + $special_nights_price + $week_end_price);
				}
			}

			if ($week_days_remaining && $week_days == 0 && $month_days == 0) {
				$month_price_days = round(($night_diff * $price_details->night) + $special_nights_price);
			}

			if ($total_month_price != 0 || $total_week_price != 0) {
				$result['total_night_price'] = $total_month_price + $total_week_price + $month_price_days;
			} else {
				$result['total_night_price'] = $total_month_price + $total_week_price + $month_price_days + $week_end_price;
			}
		} else {
			if ($price_details->weekend != 0) {
				$friday = 0;
				$satday = 0;
				$satday_count = 0;
				$friday_count = 0;

				for ($i = strtotime($checkin); $i < strtotime($checkout); $i = strtotime('+1 day', $i)) {
					if (date('N', $i) == 4) {
						if ($price_details->thursday == 'Yes') {
							$friday_count = 1 + $friday;
							$friday++;
						}
					}
					if (date('N', $i) == 5) {
						if ($price_details->friday == 'Yes') {
							$friday_count = 1 + $friday;
							$friday++;
						}
					}
					if (date('N', $i) == 6) {
						if ($price_details->saturday == 'Yes') {
							$satday_count = 1 + $satday;
							$satday++;
						}
					}
				}

				$week_end_days = ($friday_count + $satday_count) - $special_week_end_days;
				$week_end_remainig_nights = $total_nights - $week_end_days;
				$week_end_price = round($price_details->weekend * $week_end_days);
			}

			if ($week_end_days) {
				$night_diff = $week_end_remainig_nights;
			} else {
				$night_diff = $total_nights;
			}

			if ($month_days) {
				if ($price_details->month != 0) {
					$month_days_round = floor($night_diff / 28);
					$remaing_month_days = number_format($night_diff % 28);
					$average_month_price = round($price_details->month / 28);
					$round_month_price = round($month_days_round * $price_details->month);

					if ($price_details->weekend != 0) {
						$remaing_days_price = round(($remaing_month_days * $average_month_price) + $round_month_price);
						$total_month_price = round($remaing_days_price + $week_end_price);
					} else {
						$remaing_days_price = round(($remaing_month_days * $average_month_price) + $round_month_price);
						$total_month_price = round($remaing_days_price);

					}
				} else {
					$month_price = round($night_diff * $price_details->night);
					$total_month_price = round($month_price + $week_end_price);
				}
			}

			if ($week_days != 0 && $month_days == 0) {
				if ($price_details->week != 0) {
					$week_days_round = floor($night_diff / 7);
					$remaing_week_days = number_format($night_diff % 7);
					$average_week_price = round($price_details->week / 7);
					$round_week_price = round($week_days_round * $price_details->week);

					if ($price_details->weekend != 0) {
						$remaing_days_price = round(($remaing_week_days * $average_week_price) + $round_week_price);
						$total_week_price = round($remaing_days_price + $week_end_price);
					} else {
						$remaing_days_price = round(($remaing_week_days * $average_week_price) + $round_week_price);
						$total_week_price = round($remaing_days_price + $week_end_price);
					}
				} else {
					$week_price = round($night_diff * $price_details->night);
					$total_week_price = round($week_price + $week_end_price);
				}
			}

			if ($week_days_remaining && $week_days == 0 && $month_days == 0) {
				$month_price_days = round(($night_diff * $price_details->night) + $week_end_price);
			}

			if ($total_month_price != 0 || $total_week_price != 0) {
				$result['total_night_price'] = $total_month_price + $total_week_price + $month_price_days;
			} else {
				$result['total_night_price'] = $total_month_price + $total_week_price + $month_price_days;
			}
		}

		$result['rooms_price'] = round($result['total_night_price'] / $total_nights);
		$result['total_nights'] = $total_nights;
		$result['service_fee'] = number_format(($percentage / 100) * $result['total_night_price']);
		$result['host_fee'] = number_format(($host_fee_percentage / 100) * $result['total_night_price']);

		if ($guest_count > $price_details->guests) {
			$additional_guest_count = $guest_count - $price_details->guests;
			$result['additional_guest'] = $additional_guest_count * $price_details->additional_guest;
		}

		if ($price_details->security) {
			$result['security_fee'] = $price_details->security;
		}

		if ($price_details->cleaning) {
			$result['cleaning_fee'] = $price_details->cleaning;
		}

		if (Session::get('coupon_code')) {
			$coupon_code = Session::get('coupon_code');

			if ($coupon_code == 'Travel_Credit') {
				$coupon_amount = Session::get('coupon_amount');

				$total = $result['service_fee'] + $result['total_night_price'] + $result['additional_guest'] + $result['security_fee'] + $result['cleaning_fee'];

				if (SpecialOffer::find($special_offer_id)) {
					$total = SpecialOffer::find($special_offer_id)->price + $result['service_fee'];
				}

				$result['coupon_amount'] = ($total >= $coupon_amount) ? $coupon_amount : $total;
			} else {
				$coupon_details = CouponCode::where('coupon_code', $coupon_code)->get();
				$code = Session::get('currency');
				$result['coupon_amount'] = $this->currency_convert($coupon_details[0]->currency_code, $code, $coupon_details[0]->amount);
			}
			$result['coupon_code'] = $coupon_code;
		}

		$result['total'] = $result['service_fee'] + $result['total_night_price'] + $result['additional_guest'] + $result['security_fee'] + $result['cleaning_fee'] - $result['coupon_amount'];

		if (SpecialOffer::find($special_offer_id)) {
			$result['total'] = SpecialOffer::find($special_offer_id)->price + $result['service_fee'] - $result['coupon_amount'];
			$result['total_night_price'] = SpecialOffer::find($special_offer_id)->price;
			$result['rooms_price'] = SpecialOffer::find($special_offer_id)->price / $total_nights;
		}

		$result['payout'] = $result['total'] - $result['service_fee'] - $result['host_fee'];
		$result['subtotal'] = $result['total_night_price'] + $result['additional_guest'] + $result['security_fee'] + $result['cleaning_fee'];

		if (SpecialOffer::find($special_offer_id)) {
			$result['subtotal'] = SpecialOffer::find($special_offer_id)->price;
		}

		$result['currency'] = $price_details->code;

		return json_encode($result);
	}

	/**
	 * Get days between two dates
	 *
	 * @param date $sStartDate  Start Date
	 * @param date $sEndDate    End Date
	 * @return array $days      Between two dates
	 */
	public function get_days($sStartDate, $sEndDate) {
		$aDays[] = $sStartDate;
		$sCurrentDate = $sStartDate;

		while ($sCurrentDate < $sEndDate) {
			$sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
			$aDays[] = $sCurrentDate;
		}

		return $aDays;
	}

	/**
	 * Currency Convert
	 *
	 * @param int $from   Currency Code From
	 * @param int $to     Currency Code To
	 * @param int $price  Price Amount
	 * @return int Converted amount
	 */
	public function currency_convert($from = '', $to = '', $price) {
		if ($from == '') {
			if (Session::get('currency')) {
				$from = Session::get('currency');
			} else {
				$from = Currency::where('default_currency', 1)->first()->code;
			}

		}

		if ($to == '') {
			if (Session::get('currency')) {
				$to = Session::get('currency');
			} else {
				$to = Currency::where('default_currency', 1)->first()->code;
			}

		}

		$rate = Currency::whereCode($from)->first()->rate;

		$usd_amount = $price / $rate;

		$session_rate = Currency::whereCode($to)->first()->rate;

		return round($usd_amount * $session_rate);
	}

	/**
	 * Currency Rate
	 *
	 * @param int $from   Currency Code From
	 * @param int $to     Currency Code To
	 * @return int Converted Currency Rate
	 */
	public function currency_rate($from, $to) {
		$from_rate = Currency::whereCode($from)->first()->rate;
		$to_rate = Currency::whereCode($to)->first()->rate;

		return round($from_rate / $to_rate);
	}

	/**
	 * Date Convert
	 *
	 * @param date $date   Given Date
	 * @return date Converted new date format
	 */
	public function date_convert($date) {
		return date('Y-m-d', strtotime($date));
	}
	/**
	 * Penalty Amount Check
	 *
	 * @param total $amount   Given amount
	 * @return check if any penalty for this host then renturn remainning amount
	 */

	public function check_host_penalty($penalty, $reservation_amount, $reservation_currency_code) {
		$penalty_id1 = '';
		$penalty_id2 = '';

		$penalty_amt1 = '';
		$penalty_amt2 = '';

		if ($penalty->count() > 0) {

			$host_amount = $reservation_amount;

			foreach ($penalty as $pen) {

				$host_amount = $this->currency_convert($reservation_currency_code, $pen->currency_code, $host_amount);

				$remaining_amount = $pen->remain_amount;

				if ($host_amount > $remaining_amount) {
					$host_amount = $host_amount - $remaining_amount;

					$penalty = HostPenalty::find($pen->id);

					$penalty->remain_amount = 0;
					$penalty->status = "Completed";

					$penalty->save();

					$penalty_id1 .= $pen->id . ',';

					$penalty_amt1 .= $remaining_amount . ',';

				} else {

					$amount_reamining = $remaining_amount - $host_amount;

					$penalty = HostPenalty::find($pen->id);

					$penalty->remain_amount = $amount_reamining;

					$penalty->save();

					$penalty_id2 .= $pen->id . ',';

					$penalty_amt2 .= $host_amount . ',';

					$host_amount = 0;
				}

				$host_amount = $this->currency_convert($pen->currency_code, $reservation_currency_code, $host_amount);

			}

			$pty_id = $penalty_id1 . $penalty_id2;

			$penalty_id = rtrim($pty_id, ',');

			$pty_amt = $penalty_amt1 . $penalty_amt2;

			$penalty_amount = rtrim($pty_amt, ',');

		} else {
			$host_amount = $reservation_amount;

			$penalty_id = 0;

			$penalty_amount = 0;
		}

		$result['host_amount'] = $host_amount;
		$result['penalty_id'] = $penalty_id;
		$result['penalty_amount'] = $penalty_amount;

		return $result;

	}

	public function revert_travel_credit($reservation_id) {

		$applied_referrals = AppliedTravelCredit::whereReservationId($reservation_id)->get();

		foreach ($applied_referrals as $row) {
			$referral = Referrals::find($row->referral_id);

			if ($row->type == 'main') {
				$referral->credited_amount = $referral->credited_amount + $this->currency_convert($row->currency_code, $referral->currency_code, $row->original_amount);
			} else {
				$referral->friend_credited_amount = $referral->friend_credited_amount + $this->currency_convert($row->currency_code, $referral->currency_code, $row->original_amount);
			}

			$referral->save();

			$applied_referrals = AppliedTravelCredit::find($row->id)->delete();
		}

	}
}
