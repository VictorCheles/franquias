<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Pedido
 *
 * @property int $id
 * @property int $loja_id
 * @property int $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $data_entrega
 * @property float $multa
 * @property string|null $observacoes
 * @property \Carbon\Carbon|null $recebido_em
 * @property int|null $recebido_por_id
 * @property-read mixed $produtos_formatted
 * @property-read mixed $status_formatted
 * @property-read \App\Models\Loja $loja
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PedidoMensagem[] $pedido_mensagem
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Produto[] $produtos
 * @property-read \App\User|null $recebido_por
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pedido whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pedido whereDataEntrega($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pedido whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pedido whereLojaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pedido whereMulta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pedido whereObservacoes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pedido whereRecebidoEm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pedido whereRecebidoPorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pedido whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Pedido whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pedido extends Model
{
    const STATUS_SOLICITADO = 1;
    const STATUS_EM_ANDAMENTO = 2;
    const STATUS_FATURADO = 3;
    const STATUS_CONCLUIDO = 4;
    const STATUS_RECEBIDO = 5;

    public static $mapStatus = [
        self::STATUS_SOLICITADO => 'Solicitado',
        self::STATUS_EM_ANDAMENTO => 'Em andamento',
        self::STATUS_FATURADO => 'Faturado',
        self::STATUS_CONCLUIDO => 'Concluído',
        self::STATUS_RECEBIDO => 'Recebido',
    ];

    public static $mapStatusFormatted = [
        self::STATUS_SOLICITADO => '<span class="label label-info">Solicitado</span>',
        self::STATUS_EM_ANDAMENTO => '<span class="label label-danger">Em andamento</span>',
        self::STATUS_FATURADO => '<span class="label label-warning">Faturado</span>',
        self::STATUS_CONCLUIDO => '<span class="label label-success">Concluído</span><br><span class="label label-warning">Aguardando confirmação de recebimento</span>',
        self::STATUS_RECEBIDO => '<span class="label label-success">Recebido</span>',
    ];

    protected $fillable = [
        'loja_id', 'status', 'data_entrega', 'multa', 'observacoes', 'recebido_em', 'recebido_por_id',
    ];

    protected $dates = [
        'data_entrega', 'recebido_em',
    ];

    public function getStatusFormattedAttribute()
    {
        return self::$mapStatusFormatted[$this->status];
    }

    public function setRecebidoEmAttribute($value)
    {
        $this->attributes['status'] = self::STATUS_RECEBIDO;
        $this->attributes['recebido_em'] = $value;
    }

    public function getProdutosFormattedAttribute()
    {
        $produtos = $this->produtos()->get();

        ob_start(); ?>
    	<table class="table table-bordered table-striped">
    		<tr>
    			<th>Nome</th>
    			<th>Valor unitário</th>
    			<th>Quantidade</th>
    			<th>Peso</th>
    			<th>Subtotal</th>
    		</tr>
    		<?php
            foreach ($produtos as $item) {
                ?>
    			<tr>
    				<td><?php echo $item->nome ?></td>
    				<td><?php echo maskMoney($item['pivot']->preco) ?></td>
    				<td><?php echo $item['pivot']->quantidade ?></td>
    				<td><?php echo $item['pivot']->quantidade * $item->peso ?>kg</td>
    				<td><?php echo maskMoney($item['pivot']->quantidade * $item['pivot']->preco) ?></td>
    			</tr>
				<?php
            } ?>
    	</table>
		<?php
        return ob_get_clean();
    }

    public function valorTotal()
    {
        $produtos = $this->produtos()->get();
        $total = 0;
        foreach ($produtos as $item) {
            $total += $item['pivot']->preco * $item->pivot['quantidade'];
        }

        return $total;
    }

    public function pesoTotal()
    {
        $produtos = $this->produtos()->get();
        $total = 0;
        foreach ($produtos as $item) {
            $total += $item->peso * $item->pivot['quantidade'];
        }

        return $total;
    }

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'pedido_produtos', 'pedido_id', 'produto_id')->withPivot('quantidade', 'preco');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id', 'id');
    }

    public function recebido_por()
    {
        return $this->belongsTo(User::class, 'recebido_por_id', 'id');
    }

    public function pedido_mensagem()
    {
        return $this->hasMany(PedidoMensagem::class);
    }
}
