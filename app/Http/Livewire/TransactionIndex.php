<?php

namespace App\Http\Livewire;

use App\Models\Item;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Services\TransactionsServices;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class TransactionIndex extends Component
{
    use WithPagination;

    protected $listeners = ['TransactionsDataChanged' => '$refresh'];

    public $items;
    public $stores;
    public $item_id;
    public $store_id;
    public $pagination = 9;

    public function mount()
    {
        $this->items = Item::whereHas('details')->get();
        $this->stores = Store::get();
        $this->item_id = '';
    }

    public function pdf($transaction_id)
    {
        $transaction = Transaction::query()
            ->where('id', $transaction_id)
            ->with('details.item')
            ->with('details.source_store')
            ->with('details.destination_store')
            ->first();
        $view = view('pdf.transaction', compact('transaction'));
        $html = $view->render();
        Pdf::loadHTML($html)->save(public_path() . '/transaction.pdf');
        $this->redirect(asset('') . 'transaction.pdf');
    }

    public function delete($transaction)
    {

        DB::beginTransaction();
        try {
            $current_transaction = TransactionDetail::find($transaction['id']);
            $current_parent_transaction = $current_transaction->transaction;
            $current_transaction->delete();
            (new TransactionsServices())->syncItemStoreTable();
            $this->emit('TransactionsDataChanged');
            DB::commit();
            if($current_parent_transaction->details->count()== 0){
                $current_parent_transaction->delete();
            }
        } catch (\Exception $e) {
            DB::rollback();
            $this->addError('quantity_error', $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.transaction-index', [
            'transaction_details' => TransactionDetail::query()
                ->when($this->item_id, function ($q) {
                    $q->where('item_id', $this->item_id);
                })
                ->when($this->store_id, function ($q) {
                    $q->where(function ($q) {
                        $q->where('source_store_id', $this->store_id);
                        $q->orWhere('destination_store_id', $this->store_id);
                    });
                })
                ->with('transaction')
                ->with('item')
                ->with('source_store')
                ->with('destination_store')
                ->orderBy('id', 'desc')
                ->paginate($this->pagination),
        ]);
    }
}
