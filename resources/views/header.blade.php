<Devengados>   
   <Primas Cantidad='0{{-- $value["cantidad_primas"] --}}'  Pago='{{ $value["pago_salarial"] }}' PagoNS='{{ $value["pago_no_salariales"] }}'/>
   <Cesantias Pago='{{ $value["aplica_cesantias"] }}' Porcentaje='{{ $value["porcentaje"] }}' PagoIntereses='{{ $value["pago_intereses"] }}'/>
   <Bonificaciones>
      <Bonificacion BonificacionS='{{ $value["bonificacion_salarial"]}}' BonificacionNS='{{ $value["bonificacion_no_salarial"]}}'/> 
   </Bonificaciones>
   <Auxilios>
      <Auxilio AuxilioS='{{ $value["auxilios_salarial"]}}' AuxilioNS='{{ $value["auxilio_no_salarial"]}}'/>
   </Auxilios>
</Devengados>