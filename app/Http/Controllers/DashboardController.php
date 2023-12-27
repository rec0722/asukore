<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
	/**
	 * 新しいDashboardインスタンスの生成
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth:web');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		// 当日の日報を取得
		$report = Report::whereRaw(
			'user_id = :user_id AND report_date = :report_date',
			[
				':user_id' => Auth::user()->id,
				':report_date' => date('Y-m-d')
			]
		)->first();

		// 日報がある場合
		if (!is_null($report)) {
			$link['url'] = 'report.edit';
			$link['id'] = $report['id'];
			$link['str'] = '編集';
		} else {
			$link['url'] = 'report.create';
			$link['id'] = null;
			$link['str'] = '作成';
		}

		return
			view(
				'dashboard',
				compact(
					'report',
					'link'
				)
			);
	}
}
