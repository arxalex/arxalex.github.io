export interface ButtonModel {
  text: string;
  link: string;
  type: ButtonType
}

export enum ButtonType {
  Primary = 'primary',
  Secondary = 'secondary',
  Github = 'github',
  PlayMarket = 'play-market',
  AppStore = 'app-store'
}
