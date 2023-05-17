<?php

namespace App\Http\Livewire;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;


class TransactionIndex extends Component
{
    use WithPagination;

    protected $listeners = ['TransactionsDataChanged' => '$refresh'];

    public $search;
    public $pagination = 9;

    public function updatingSearch()
    {
        $this->resetPage();
    }












    public function pdf($transaction_id)
    {
        $transaction = Transaction::where('id',$transaction_id)->with('details.item')->first();
        $view = view('pdf.transaction',compact('transaction'));
        $html = $view->render();
        Pdf::loadHTML($html)->save(public_path() . '/transaction.pdf');
        $this->redirect(asset('') . 'transaction.pdf');
    }
























    public function delete($transaction)
    {
        $current_transaction = Transaction::find($transaction['id']);
        $current_transaction->details()->delete();
        $current_transaction->delete();
        $this->emit('TransactionsDataChanged');
    }
    public function render()
    {
        return view('livewire.transaction-index', [
            'transactions' => Transaction::when($this->search, function ($q) {
                $q->where('id', $this->search );
            })
            ->with(['source_store','destination_store'])
            ->paginate($this->pagination),
        ]);
    }
}
