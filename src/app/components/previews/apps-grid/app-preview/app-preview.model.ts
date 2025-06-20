import {ButtonModel} from '../../button-cta/button.model';

export interface AppPreviewModel {
  title: string;
  description: string;
  previewImage: string;
  buttons: [ButtonModel],
  tags?: [Tags];
}

export enum Tags {
  NEW = 'new',
  FEATURED = 'featured',
  POPULAR = 'popular',
}
