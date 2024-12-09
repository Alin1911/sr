<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Transaction;

class SetUserEventData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                // 1. Obținem biletele asociate acestui utilizator
                $transactions = Transaction::where('user_id', $user->id)->get();
                if($transactions->count() == 0){
                    continue;
                }
                $tickets = Ticket::whereIn('transactionId', $transactions->pluck('id'))->get();
                if ($tickets->isEmpty()) {
                    continue; 
                }
                $tickets->load('performance.event.category','performance.hall.city');

                // 2. Creăm map-uri pentru fiecare câmp
                $hallMap = [];
                $cityMap = [];
                $categoryMap = [];
                $promoterMap = [];

                foreach ($tickets as $ticket) {
                    // Adăugăm la map-uri numărul de apariții pentru fiecare ID
                    $hallMap[$ticket->performance->hall_id] = ($hallMap[$ticket->performance->hall_id] ?? 0) + 1;
                    $cityMap[$ticket->performance->hall->city_id] = ($cityMap[$ticket->performance->hall->city_id] ?? 0) + 1;
                    $categoryMap[$ticket->performance->event->category_id] = ($categoryMap[$ticket->performance->event->category_id] ?? 0) + 1;
                    $promoterMap[$ticket->performance->promoter_id] = ($promoterMap[$ticket->performance->promoter_id] ?? 0) + 1;
                }

                // 3. Găsim ID-ul cu cea mai mare frecvență pentru fiecare câmp
                $hallId = $this->getMaxKey($hallMap);
                $cityId = $this->getMaxKey($cityMap);
                $categoryId = $this->getMaxKey($categoryMap);
                $promoterId = $this->getMaxKey($promoterMap);

                // 4. Actualizăm utilizatorul
                $user->update([
                    'hall_id_e' => $hallId,
                    'city_id_e' => $cityId,
                    'category_id_e' => $categoryId,
                    'promoter_id_e' => $promoterId,
                ]);
            }
        });

        $this->info('Datele utilizatorilor au fost actualizate cu succes!');
    }

        /**
     * Găsește cheia cu valoarea maximă dintr-un map.
     *
     * @param  array  $map
     * @return mixed
     */
    protected function getMaxKey($map)
    {
        return array_search(max($map), $map);
    }
}
