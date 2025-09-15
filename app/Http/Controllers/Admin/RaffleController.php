<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyRaffle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RaffleController extends Controller
{
    public function index()
    {
        $raffles = MonthlyRaffle::with('winner')
            ->orderBy('raffle_year', 'desc')
            ->orderBy('raffle_month', 'desc')
            ->paginate(12);

        return view('admin.raffles.index', compact('raffles'));
    }

    public function show(MonthlyRaffle $raffle)
    {
        $raffle->load('winner');
        $eligibleUsers = $raffle->getEligibleUsers();

        return view('admin.raffles.show', compact('raffle', 'eligibleUsers'));
    }

    public function conductDraw(MonthlyRaffle $raffle)
    {
        try {
            if ($raffle->status !== 'active') {
                return redirect()->back()->with('error', 'Raffle is not active');
            }

            $winner = $raffle->conductDraw();

            // Create notification for the winner
            $winner->userNotifications()->create([
                'title' => '🎉 Congratulations! You Won the Monthly Raffle!',
                'message' => "You've won the {$raffle->title}! An iPhone Air will be delivered to you soon. Contact support for delivery details.",
                'type' => 'raffle_win',
                'data' => [
                    'raffle_id' => $raffle->id,
                    'prize' => 'iPhone Air',
                    'month' => $raffle->raffle_month,
                    'year' => $raffle->raffle_year,
                ]
            ]);

            // Create notification for all participants
            $eligibleUsers = $raffle->getEligibleUsers();
            foreach ($eligibleUsers as $user) {
                if ($user->id !== $winner->id) {
                    $user->userNotifications()->create([
                        'title' => 'Monthly Raffle Results',
                        'message' => "The {$raffle->title} has ended. Better luck next month! Keep logging in daily to increase your chances.",
                        'type' => 'raffle_result',
                        'data' => [
                            'raffle_id' => $raffle->id,
                            'winner_name' => $winner->name,
                            'your_tickets' => $user->total_tickets,
                        ]
                    ]);
                }
            }

            return redirect()->back()->with('success', "Raffle conducted successfully! Winner: {$winner->name}");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to conduct raffle: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.raffles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'raffle_year' => 'required|integer|min:2024|max:2030',
            'raffle_month' => 'required|integer|min:1|max:12',
        ]);

        // Check if raffle already exists
        $existing = MonthlyRaffle::where('raffle_year', $request->raffle_year)
            ->where('raffle_month', $request->raffle_month)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Raffle already exists for this month');
        }

        MonthlyRaffle::create($request->all());

        return redirect()->route('admin.raffles.index')->with('success', 'Raffle created successfully');
    }

    public function edit(MonthlyRaffle $raffle)
    {
        return view('admin.raffles.edit', compact('raffle'));
    }

    public function update(Request $request, MonthlyRaffle $raffle)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $raffle->update($request->only(['title', 'description']));

        return redirect()->route('admin.raffles.index')->with('success', 'Raffle updated successfully');
    }

    public function destroy(MonthlyRaffle $raffle)
    {
        if ($raffle->status === 'drawn') {
            return redirect()->back()->with('error', 'Cannot delete a completed raffle');
        }

        $raffle->delete();

        return redirect()->route('admin.raffles.index')->with('success', 'Raffle deleted successfully');
    }
}
