import {Component, Input} from '@angular/core';
import {BudgetType} from '@angular/build/private';
import {ButtonModel, ButtonType} from './button.model';

@Component({
  selector: 'app-button-cta',
  imports: [],
  templateUrl: './button-cta.component.html',
  styleUrl: './button-cta.component.scss'
})
export class ButtonCta {
  @Input() button!: ButtonModel;
  protected readonly ButtonType = ButtonType;
}
