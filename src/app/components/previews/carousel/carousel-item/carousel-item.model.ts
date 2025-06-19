import {ButtonModel} from '../../button-cta/button.model';

export interface CarouselItemModel {
  title: string;
  description: string;
  icon: string;
  buttons: [ButtonModel]
}
